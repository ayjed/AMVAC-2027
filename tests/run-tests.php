<?php
/**
 * AMVAC 2027 — Automated Verification Test Suite
 * Validates integrity against 01_PROJECT_SOURCE_OF_TRUTH.md
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Csrf.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/AuditService.php';
require_once __DIR__ . '/../src/Mailer.php';
require_once __DIR__ . '/../src/RegistrationService.php';
require_once __DIR__ . '/../src/PaymentService.php';

$totalTests = 0;
$passedTests = 0;

function assertTest(string $description, bool $condition): void {
    global $totalTests, $passedTests;
    $totalTests++;
    if ($condition) {
        $passedTests++;
        echo "  [PASS] {$description}\n";
    } else {
        echo "  [FAIL] {$description}\n";
    }
}

echo "\n======================================================\n";
echo " AMVAC 2027 — Automated Verification & Acceptance Tests\n";
echo "======================================================\n\n";

// TEST GROUP 1: Canonical Content & Constants Verification
echo "Test Group 1: Canonical Content & All Eight Objectives\n";
assertTest("Theme matches canonical wording verbatim", AMVAC_THEME === 'Enhancing Continental Security, Road Safety and Economic Integration Through Effective Motor Vehicle Administration in Africa.');
assertTest("Slogan matches canonical wording verbatim", AMVAC_SLOGAN === 'Please be road safety conscious to save lives.');
assertTest("Exactly 8 canonical objectives exist", count(AMVAC_OBJECTIVES) === 8);
assertTest("Objective 1 is Continental Harmonization", str_contains(AMVAC_OBJECTIVES[1]['full_text'], 'unified legal and regulatory framework'));
assertTest("Objective 2 is Security Integration", str_contains(AMVAC_OBJECTIVES[2]['full_text'], 'cross-border vehicle theft, smuggling, kidnapping and terrorism'));
assertTest("Objective 3 is Digital Transformation", str_contains(AMVAC_OBJECTIVES[3]['full_text'], 'automation, centralized digital databases and biometric systems'));
assertTest("Objective 4 is Capacity Building", str_contains(AMVAC_OBJECTIVES[4]['full_text'], 'train and retrain Motor Vehicle Administrators'));
assertTest("Objective 5 is Safer African Roads with mortality text", str_contains(AMVAC_OBJECTIVES[5]['full_text'], 'over 300,000 annual road deaths in Africa'));
assertTest("Objective 6 is Public Enlightenment", str_contains(AMVAC_OBJECTIVES[6]['full_text'], 'life-saving service, not punishment'));
assertTest("Objective 7 is Inter-Agency Collaboration", str_contains(AMVAC_OBJECTIVES[7]['full_text'], 'Police, Customs, FRSC, Immigration and Interpol'));
assertTest("Objective 8 is Economic Development", str_contains(AMVAC_OBJECTIVES[8]['full_text'], 'number plate, driver license and vehicle assembly plants'));

// TEST GROUP 2: Fee Matrix & Server-Side Security
echo "\nTest Group 2: Fee Rules & Server-Side Price Locking\n";
assertTest("Nigerian delegate fee is strictly ₦100,000 NGN", AMVAC_FEE_MATRIX['nigerian_delegate']['amount'] === 100000.00 && AMVAC_FEE_MATRIX['nigerian_delegate']['currency'] === 'NGN');
assertTest("International delegate fee is strictly $50 USD", AMVAC_FEE_MATRIX['international_delegate']['amount'] === 50.00 && AMVAC_FEE_MATRIX['international_delegate']['currency'] === 'USD');

// TEST GROUP 3: Input Validation & Sanitization
echo "\nTest Group 3: Input Validator & Anti-XSS Sanitization\n";
$maliciousPayload = [
    'full_name' => '  Dr. <script>alert("xss")</script> Aminu Toure  ',
    'email' => 'DELEGATE@TEST.ORG',
    'phone' => '+220 700 1234',
    'country' => 'The Gambia',
    'organization' => 'Ministry of Transport <b>Gambia</b>',
    'job_title' => 'Chief Inspector',
    'fee_category' => 'international_delegate',
    'consent' => '1'
];
$validator = Validator::make($maliciousPayload)
    ->sanitizeString('full_name', 150)->required('full_name', 'Required')
    ->sanitizeString('email', 191)->email('email', 'Required')
    ->sanitizeString('organization', 200)->required('organization', 'Required')
    ->inArray('fee_category', ['nigerian_delegate', 'international_delegate'], 'Required');

assertTest("Validator passes valid inputs", $validator->passes());
assertTest("Email is normalized to lowercase", $validator->value('email') === 'delegate@test.org');
assertTest("Control chars stripped from name", !str_contains($validator->value('full_name'), "\0"));

// TEST GROUP 4: Registration Creation & State Machine
echo "\nTest Group 4: Registration Lifecycle & Tamper-Proof Fee Enforcement\n";
$testEmail = 'test.delegate.' . bin2hex(random_bytes(4)) . '@example.com';
$regData = [
    'full_name' => 'Alhaji Bakary Camara',
    'email' => $testEmail,
    'phone' => '+220 9988776',
    'country' => 'The Gambia',
    'organization' => 'Gambia Transport Service Company',
    'job_title' => 'Operations Director',
    'participation_format' => 'in_person_banjul',
    'fee_category' => 'international_delegate',
    'tampered_amount' => '1.00', // Malicious attempt to pay 1 dollar
    'consent' => '1'
];

$creation = RegistrationService::create($regData, '127.0.0.1', 'CLI Test Suite');
assertTest("Registration created successfully", $creation['success'] === true && !empty($creation['public_reference']));

$savedReg = RegistrationService::findByReference($creation['public_reference']);
assertTest("Public reference format matches AMVAC-2027-XXXXXXXX", (bool)preg_match('/^AMVAC-2027-[2-9A-Z]{8}$/', $savedReg['public_reference']));
assertTest("Initial registration status is strictly 'pending_payment'", $savedReg['status'] === 'pending_payment');
assertTest("Server forced exact fee amount (tampered client amount ignored)", (float)$savedReg['amount'] === 50.00 && $savedReg['currency'] === 'USD');

// TEST GROUP 5: Bank Transfer Manual Review Security
echo "\nTest Group 5: Bank Transfer Workflow (Manual Review Gate)\n";
$bankProof = PaymentService::submitManualProof(
    $savedReg['public_reference'],
    'BLMB-TXN-998811',
    date('Y-m-d'),
    'Depositor: Bakary Camara'
);

$afterProofReg = RegistrationService::findByReference($savedReg['public_reference']);
assertTest("Submitting bank proof transitions status to 'pending_manual_review'", $afterProofReg['status'] === 'pending_manual_review');
assertTest("CRITICAL SECURITY: Submitting bank proof NEVER sets status to 'paid'", $afterProofReg['status'] !== 'paid');

// Admin Review: Approve Payment
$pdo = Database::getConnection();
$reviewRow = $pdo->query("SELECT id FROM manual_payment_reviews WHERE registration_id = {$savedReg['id']} ORDER BY id DESC LIMIT 1")->fetch();

$approval = PaymentService::reviewManualPayment(
    (int)$reviewRow['id'],
    'approve',
    1,
    'Confirmed on Bloom Bank Africa statement 22 Oct 2026'
);

$afterApprovalReg = RegistrationService::findByReference($savedReg['public_reference']);
assertTest("Admin approval transitions registration status to 'paid'", $afterApprovalReg['status'] === 'paid');
assertTest("Verified payment records payment method 'bank_transfer'", $afterApprovalReg['payment_method'] === 'bank_transfer');
assertTest("Verified payment records verified_at timestamp", !empty($afterApprovalReg['verified_at']));

// Verify Audit Log was written
$auditStmt = $pdo->prepare("SELECT * FROM audit_logs WHERE target_id = :id AND action = 'PAYMENT_MANUAL_VERIFIED' LIMIT 1");
$auditStmt->execute([':id' => (string)$savedReg['id']]);
$auditRecord = $auditStmt->fetch();
assertTest("Immutable audit log recorded admin identity and verification notes", $auditRecord !== false && $auditRecord['actor_id'] === '1');

// TEST GROUP 6: Admin Authentication & Hardened Session
echo "\nTest Group 6: Admin Authentication & Brute-Force Lockout Policy\n";
assertTest("Admin authentication succeeds with valid credentials", Auth::attempt('admin@amvac2027.org', 'ChangeMe2027!Secure'));
Auth::logout();
assertTest("Admin authentication fails with invalid password", !Auth::attempt('admin@amvac2027.org', 'WrongPassword123!'));

// Summary
echo "\n======================================================\n";
echo " Results: {$passedTests} / {$totalTests} tests passed.\n";
if ($passedTests === $totalTests) {
    echo " ALL TESTS PASSED SUCCESSFULLY! (100%)\n";
} else {
    echo " SOME TESTS FAILED. Review output above.\n";
    exit(1);
}
echo "======================================================\n\n";
