<?php
/**
 * AMVAC 2027 — Front Controller & Canonical Router
 * Strictly serves 5 public pages + minimal private admin area
 */

declare(strict_types=1);

// Static file fallback for PHP built-in web server
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($filePath)) {
        return false;
    }
}

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Csrf.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/AuditService.php';
require_once __DIR__ . '/../src/Mailer.php';
require_once __DIR__ . '/../src/RegistrationService.php';
require_once __DIR__ . '/../src/PaymentService.php';

// Normalize URI path
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$currentRoute = rtrim($requestUri, '/') ?: '/';

// Security Response Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

try {
    // --------------------------------------------------------------------------
    // 1. Public Pages (Strict Five Canonical Pages)
    // --------------------------------------------------------------------------

    // Route: GET /
    if ($currentRoute === '/' && $requestMethod === 'GET') {
        ob_start();
        require __DIR__ . '/../views/pages/home.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
        exit;
    }

    // Route: GET /about
    if ($currentRoute === '/about' && $requestMethod === 'GET') {
        ob_start();
        require __DIR__ . '/../views/pages/about.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
        exit;
    }

    // Route: GET /programme
    if ($currentRoute === '/programme' && $requestMethod === 'GET') {
        ob_start();
        require __DIR__ . '/../views/pages/programme.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
        exit;
    }

    // Route: GET /register
    if ($currentRoute === '/register' && $requestMethod === 'GET') {
        ob_start();
        require __DIR__ . '/../views/pages/register.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
        exit;
    }

    // Route: POST /register (Delegate Submission)
    if ($currentRoute === '/register' && $requestMethod === 'POST') {
        Csrf::validateOrFail($_POST['csrf_token'] ?? null);

        $validator = Validator::make($_POST)
            ->sanitizeString('full_name', 150)->required('full_name', 'Full name is required.')
            ->sanitizeString('email', 191)->email('email', 'A valid email address is required.')
            ->sanitizeString('phone', 30)->required('phone', 'Phone number is required.')
            ->sanitizeString('country', 100)->required('country', 'Country is required.')
            ->sanitizeString('organization', 200)->required('organization', 'Organization is required.')
            ->sanitizeString('job_title', 150)->required('job_title', 'Designation / Job Title is required.')
            ->sanitizeString('participation_format', 50)->inArray('participation_format', ['in_person_banjul', 'virtual_hybrid'], 'Valid format is required.')
            ->sanitizeString('fee_category', 50)->inArray('fee_category', array_keys(AMVAC_FEE_MATRIX), 'Valid fee category is required.')
            ->required('consent', 'You must agree to the accreditation consent terms.');

        if (!$validator->passes()) {
            $errors = $validator->errors();
            set_flash('error', reset($errors));
            header('Location: /register');
            exit;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        $result = RegistrationService::create($validator->sanitizedData(), $ip, $ua);

        if ($result['is_existing']) {
            set_flash('info', "An active registration already exists for this email with reference {$result['public_reference']}.");
        } else {
            set_flash('success', "Registration submitted successfully! Your official reference is {$result['public_reference']}. Please proceed with payment below.");
        }

        header('Location: /register?ref=' . urlencode($result['public_reference']));
        exit;
    }

    // Route: POST /register/submit-payment (Bank Transfer Reference Submission)
    if ($currentRoute === '/register/submit-payment' && $requestMethod === 'POST') {
        Csrf::validateOrFail($_POST['csrf_token'] ?? null);

        $ref = trim($_POST['public_reference'] ?? '');
        $bankRef = trim($_POST['bank_reference'] ?? '');
        $depositDate = trim($_POST['deposit_date'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if (empty($ref) || empty($bankRef)) {
            set_flash('error', 'Registration reference and bank transfer reference are both required.');
            header('Location: /register' . ($ref ? '?ref=' . urlencode($ref) : ''));
            exit;
        }

        $res = PaymentService::submitManualProof($ref, $bankRef, $depositDate, $notes);
        set_flash('info', $res['message']);
        header('Location: /register?ref=' . urlencode($ref));
        exit;
    }

    // Route: POST /register/sandbox-pay (Sandbox Simulator)
    if ($currentRoute === '/register/sandbox-pay' && $requestMethod === 'POST') {
        Csrf::validateOrFail($_POST['csrf_token'] ?? null);

        $ref = trim($_POST['public_reference'] ?? '');
        if (empty($ref)) {
            set_flash('error', 'Registration reference is required.');
            header('Location: /register');
            exit;
        }

        $res = PaymentService::processSandboxPayment($ref);
        set_flash('success', $res['message']);
        header('Location: /register?ref=' . urlencode($ref));
        exit;
    }

    // Route: GET /contact
    if ($currentRoute === '/contact' && $requestMethod === 'GET') {
        ob_start();
        require __DIR__ . '/../views/pages/contact.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
        exit;
    }

    // Route: POST /contact (Inquiry Submission)
    if ($currentRoute === '/contact' && $requestMethod === 'POST') {
        Csrf::validateOrFail($_POST['csrf_token'] ?? null);

        $validator = Validator::make($_POST)
            ->sanitizeString('full_name', 150)->required('full_name', 'Full Name is required.')
            ->sanitizeString('email', 191)->email('email', 'A valid email is required.')
            ->sanitizeString('phone', 30)
            ->sanitizeString('subject', 200)->required('subject', 'Subject category is required.')
            ->sanitizeString('message', 5000)->required('message', 'Message cannot be empty.');

        if (!$validator->passes()) {
            $errors = $validator->errors();
            set_flash('error', reset($errors));
            header('Location: /contact');
            exit;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO contact_enquiries (full_name, email, phone, subject, message, ip_address, created_at)
            VALUES (:name, :email, :phone, :subject, :msg, :ip, NOW())
        ");
        $stmt->execute([
            ':name'    => $validator->value('full_name'),
            ':email'   => $validator->value('email'),
            ':phone'   => $validator->value('phone'),
            ':subject' => $validator->value('subject'),
            ':msg'     => $validator->value('message'),
            ':ip'      => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'
        ]);

        AuditService::log('delegate', null, 'CONTACT_ENQUIRY_SUBMITTED', 'enquiry', (string)$pdo->lastInsertId());

        set_flash('success', 'Your inquiry has been transmitted to the conference secretariat. A protocol officer will respond shortly.');
        header('Location: /contact');
        exit;
    }

    // --------------------------------------------------------------------------
    // 2. Webhook Route (Payment Webhook with Signature Validation)
    // --------------------------------------------------------------------------
    if ($currentRoute === '/webhook/payment' && $requestMethod === 'POST') {
        $rawPayload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
        $secretKey = getenv('PAYSTACK_SECRET_KEY') ?: '';

        // If secret key is not set, reject or respond in staging
        if (!empty($secretKey)) {
            $expected = hash_hmac('sha512', $rawPayload, $secretKey);
            if (!hash_equals($expected, $signature)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid HMAC signature']);
                exit;
            }
        }

        $event = json_decode($rawPayload, true);
        if ($event && !empty($event['event'])) {
            $pdo = Database::getConnection();
            $eventId = $event['data']['id'] ?? ('EVT-' . md5($rawPayload));
            
            // Check idempotency
            $checkStmt = $pdo->prepare("SELECT id FROM payment_events WHERE event_id = :id LIMIT 1");
            $checkStmt->execute([':id' => (string)$eventId]);
            if ($checkStmt->fetch()) {
                http_response_code(200);
                echo json_encode(['status' => 'already_processed']);
                exit;
            }

            // Record event
            $insStmt = $pdo->prepare("
                INSERT INTO payment_events (provider, event_id, event_type, payload, processed_at)
                VALUES ('paystack', :eid, :etype, :payload, NOW())
            ");
            $insStmt->execute([
                ':eid'     => (string)$eventId,
                ':etype'   => $event['event'],
                ':payload' => $rawPayload
            ]);
        }

        http_response_code(200);
        echo json_encode(['status' => 'received']);
        exit;
    }

    // --------------------------------------------------------------------------
    // 3. Private Administrator Area
    // --------------------------------------------------------------------------

    // Route: GET /admin/login
    if ($currentRoute === '/admin/login' && $requestMethod === 'GET') {
        if (Auth::check()) {
            header('Location: /admin/dashboard');
            exit;
        }
        ob_start();
        require __DIR__ . '/../views/admin/login.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/admin.php';
        exit;
    }

    // Route: POST /admin/login
    if ($currentRoute === '/admin/login' && $requestMethod === 'POST') {
        Csrf::validateOrFail($_POST['csrf_token'] ?? null);

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        try {
            if (Auth::attempt($email, $password)) {
                $target = $_SESSION['redirect_after_login'] ?? '/admin/dashboard';
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $target);
                exit;
            } else {
                set_flash('error', 'Invalid administrator email or password.');
                header('Location: /admin/login');
                exit;
            }
        } catch (RuntimeException $e) {
            set_flash('error', $e->getMessage());
            header('Location: /admin/login');
            exit;
        }
    }

    // Route: GET /admin/logout
    if ($currentRoute === '/admin/logout') {
        Auth::logout();
        header('Location: /admin/login');
        exit;
    }

    // Admin Auth Guard for All Other /admin Routes
    if (str_starts_with($currentRoute, '/admin')) {
        Auth::requireAuth();

        // Route: GET /admin or /admin/dashboard
        if ($currentRoute === '/admin' || $currentRoute === '/admin/dashboard') {
            ob_start();
            require __DIR__ . '/../views/admin/dashboard.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/admin.php';
            exit;
        }

        // Route: GET /admin/registrations
        if ($currentRoute === '/admin/registrations') {
            ob_start();
            require __DIR__ . '/../views/admin/registrations.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/admin.php';
            exit;
        }

        // Route: GET /admin/payments
        if ($currentRoute === '/admin/payments' && $requestMethod === 'GET') {
            ob_start();
            require __DIR__ . '/../views/admin/payments.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/admin.php';
            exit;
        }

        // Route: POST /admin/payments/review
        if ($currentRoute === '/admin/payments/review' && $requestMethod === 'POST') {
            Csrf::validateOrFail($_POST['csrf_token'] ?? null);

            $reviewId = (int)($_POST['review_id'] ?? 0);
            $decision = trim($_POST['decision'] ?? '');
            $adminNotes = trim($_POST['admin_notes'] ?? '');
            $adminId = Auth::id() ?: 1;

            if ($reviewId <= 0 || empty($adminNotes)) {
                set_flash('error', 'Review ID and audit justification notes are required.');
                header('Location: /admin/payments');
                exit;
            }

            try {
                $res = PaymentService::reviewManualPayment($reviewId, $decision, $adminId, $adminNotes);
                set_flash('success', $res['message']);
            } catch (Throwable $e) {
                set_flash('error', 'Verification error: ' . $e->getMessage());
            }

            header('Location: /admin/payments');
            exit;
        }

        // Route: GET /admin/enquiries
        if ($currentRoute === '/admin/enquiries') {
            ob_start();
            require __DIR__ . '/../views/admin/enquiries.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/admin.php';
            exit;
        }

        // Route: GET /admin/audit
        if ($currentRoute === '/admin/audit') {
            ob_start();
            require __DIR__ . '/../views/admin/audit.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/admin.php';
            exit;
        }
    }

    // --------------------------------------------------------------------------
    // 4. 404 Not Found Handling
    // --------------------------------------------------------------------------
    http_response_code(404);
    $pageTitle = 'Page Not Found — AMVAC 2027';
    ob_start();
    ?>
    <section style="padding: var(--space-16) 0; text-align: center;">
      <div class="container container-narrow">
        <h1 style="font-family: var(--font-serif); font-size: 3rem; color: var(--navy-900); margin-bottom: var(--space-4);">404</h1>
        <h2 style="font-size: 1.5rem; margin-bottom: var(--space-4);">Page Not Found</h2>
        <p style="color: var(--slate-600); margin-bottom: var(--space-8);">
          The requested page does not exist on the official AMVAC 2027 portal. Please use the navigation links above to access the five official conference pages.
        </p>
        <a href="/" class="btn btn-navy">Return to Homepage</a>
      </div>
    </section>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../views/layouts/main.php';

} catch (Throwable $e) {
    error_log("AMVAC Unhandled Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    $statusCode = http_response_code();
    if ($statusCode < 400 || $statusCode === 200) {
        $statusCode = 500;
        http_response_code(500);
    }

    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo "<div style='font-family: monospace; padding: 20px; background: #FFF5F5; color: #C53030;'>";
        echo "<h2>AMVAC Application Error</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='font-family: sans-serif; text-align: center; padding: 60px 20px;'>";
        echo "<h1>Service Temporarily Unavailable</h1>";
        echo "<p>The conference portal encountered an unexpected error. Please refresh the page or contact the secretariat desk.</p>";
        echo "</div>";
    }
}
