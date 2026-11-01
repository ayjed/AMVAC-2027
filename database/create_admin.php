<?php
/**
 * AMVAC 2027 — Secure Administrator Setup & Password Rotation Utility
 *
 * This command-line script creates or updates an administrator account with
 * a securely hashed password. It supports secure, non-echoing interactive prompts
 * so sensitive passwords never appear in shell histories, process tables, or logs.
 *
 * Usage:
 *   Interactive mode (Recommended):
 *     php database/create_admin.php
 *
 *   Non-interactive mode (for automation):
 *     php database/create_admin.php <email> [password] [name] [role]
 */

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    echo "Forbidden: This script can only be executed via the command line interface.\n";
    exit(1);
}

require_once __DIR__ . '/../src/Database.php';

/**
 * Prompt for a password without echoing characters to the terminal.
 */
function promptHiddenPassword(string $prompt = 'Password: '): string {
    echo $prompt;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows: use PowerShell Read-Host -AsSecureString
        $cmd = 'powershell -NoProfile -Command "$p = Read-Host -AsSecureString; [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($p))"';
        $res = shell_exec($cmd);
        echo "\n";
        return trim($res ?? '');
    }

    // Unix / Linux / macOS: toggle terminal echo via stty
    $sttyState = shell_exec('stty -g');
    shell_exec('stty -echo');
    $pwd = fgets(STDIN);
    if ($sttyState) {
        shell_exec('stty ' . escapeshellarg(trim($sttyState)));
    } else {
        shell_exec('stty echo');
    }
    echo "\n";
    return trim($pwd ?? '');
}

/**
 * Prompt for standard text input.
 */
function promptInput(string $prompt, string $default = ''): string {
    if (!empty($default)) {
        echo "{$prompt} [{$default}]: ";
    } else {
        echo "{$prompt}: ";
    }
    $val = trim((string)fgets(STDIN));
    return empty($val) ? $default : $val;
}

echo "=======================================================\n";
echo " AMVAC 2027 — Secure Administrator Setup & Rotation\n";
echo "=======================================================\n\n";

// Gather arguments or trigger interactive prompts
$email = $argv[1] ?? null;
$password = $argv[2] ?? null;
$name = $argv[3] ?? null;
$role = $argv[4] ?? null;

$isInteractive = empty($argv[1]);

if ($isInteractive) {
    echo "Interactive Setup Mode (Passwords will not be displayed on screen):\n\n";
    $email = promptInput("Administrative Email");
}

$email = strtolower(trim((string)$email));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format provided: '{$email}'.\n";
    exit(1);
}

// If password is not provided via CLI argument, securely prompt with hidden input
if (empty($password)) {
    $password = promptHiddenPassword("Enter Administrator Password (min 12 chars): ");
    if (empty($password)) {
        echo "Error: Password cannot be empty.\n";
        exit(1);
    }

    $confirm = promptHiddenPassword("Confirm Administrator Password: ");
    if ($password !== $confirm) {
        echo "Error: Passwords do not match. Aborting.\n";
        exit(1);
    }
} else {
    echo "Notice: Password provided as command-line argument.\n";
    echo "        For maximum security, run without arguments to use hidden interactive entry.\n";
}

if (strlen($password) < 12) {
    echo "Error: Password must be at least 12 characters in length for administrative security.\n";
    exit(1);
}

if (empty($name)) {
    $name = $isInteractive ? promptInput("Display Name", "AMVAC Lead Administrator") : "AMVAC Lead Administrator";
}

if (empty($role)) {
    $role = $isInteractive ? promptInput("Role (super_admin or admin)", "super_admin") : "super_admin";
}

if (!in_array($role, ['super_admin', 'admin'], true)) {
    $role = 'super_admin';
}

try {
    $pdo = Database::getConnection();
} catch (Throwable $e) {
    echo "\nDatabase Error: Unable to connect to MySQL database.\n";
    echo "Please ensure MySQL is running and your .env configuration is correct.\n";
    exit(1);
}

// Generate secure BCRYPT hash with cost 12
$passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
// Clear plain password from memory immediately
unset($password, $confirm);

// Check if admin already exists
$stmt = $pdo->prepare("SELECT id, email FROM admins WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
$existing = $stmt->fetch();

if ($existing) {
    // Rotate password for existing admin
    $update = $pdo->prepare("
        UPDATE admins 
        SET password_hash = :hash,
            name = :name,
            role = :role,
            is_active = 1,
            failed_login_attempts = 0,
            locked_until = NULL,
            updated_at = NOW()
        WHERE id = :id
    ");
    $update->execute([
        ':hash' => $passwordHash,
        ':name' => $name,
        ':role' => $role,
        ':id'   => $existing['id']
    ]);

    echo "\n[SUCCESS] Administrator password successfully rotated for: {$email}\n";
    echo "          Account is active, lockout counters reset, and ready for authentication.\n";
} else {
    // Insert new administrator
    $insert = $pdo->prepare("
        INSERT INTO admins (name, email, password_hash, role, is_active, failed_login_attempts, created_at, updated_at)
        VALUES (:name, :email, :hash, :role, 1, 0, NOW(), NOW())
    ");
    $insert->execute([
        ':name'  => $name,
        ':email' => $email,
        ':hash'  => $passwordHash,
        ':role'  => $role
    ]);

    echo "\n[SUCCESS] New administrator account successfully created for: {$email}\n";
    echo "          Role: {$role} | Status: Active\n";
}
