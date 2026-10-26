<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'reply' => 'This inquiry assistant accepts POST requests only.',
        'suggestions' => []
    ]);
    exit;
}

$rawInput = file_get_contents('php://input');
$payload = json_decode($rawInput, true);

if (!is_array($payload)) {
    $payload = $_POST;
}

$message = trim((string)($payload['message'] ?? ''));

if (!isset($_SESSION['inquiry_assistant'])) {
    $_SESSION['inquiry_assistant'] = [
        'service' => '',
    ];
}

$state = &$_SESSION['inquiry_assistant'];

function normalizeMessage(string $message): string
{
    $message = strtolower($message);
    $message = preg_replace('/[^a-z0-9\s]/', ' ', $message);
    $message = preg_replace('/\s+/', ' ', $message);
    return trim($message);
}

function containsAny(string $message, array $keywords): bool
{
    foreach ($keywords as $keyword) {
        if (strpos($message, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

function response(string $reply, array $suggestions = [], array $extra = []): void
{
    echo json_encode(array_merge([
        'reply' => $reply,
        'suggestions' => $suggestions,
    ], $extra));
    exit;
}

function getServiceLabel(string $service): string
{
    if ($service === 'aics') {
        return 'AICS';
    }
    if ($service === 'solo_parent') {
        return 'Solo Parent';
    }
    return 'the service';
}

function getRequirementsReply(string $service): string
{
    if ($service === 'aics') {
        return "For AICS, the common requirements are: valid ID, barangay certificate or indigency certificate, supporting documents for the crisis situation, proof of income if available, and a letter or request if needed. Final requirements may still depend on the case assessment.";
    }

    if ($service === 'solo_parent') {
        return "For Solo Parent assistance, the common requirements are: valid ID, proof of residency, supporting documents showing solo parent status, proof of income when available, and the completed application form. The office may ask for additional supporting documents during review.";
    }

    return "Please choose a service first so I can give the correct requirements. You can ask about AICS or Solo Parent.";
}

function getEligibilityReply(string $service): string
{
    if ($service === 'aics') {
        return "AICS is usually for individuals or families experiencing crisis situations such as medical, funeral, educational, food, shelter, or emergency-related needs. Eligibility is still subject to assessment by the social worker.";
    }

    if ($service === 'solo_parent') {
        return "Solo Parent services are for qualified solo parents who are raising their children alone and can provide supporting documents for their status. Final eligibility is still subject to office verification and assessment.";
    }

    return "I can explain eligibility for AICS or Solo Parent. Which one do you want to ask about?";
}

function getApplyReply(string $service): string
{
    if ($service === 'aics') {
        return "To apply for AICS in this system: open the AICS service page, log in, fill out the application form, submit the required details, and wait for assessment. After submission, you can monitor your form status in the system.";
    }

    if ($service === 'solo_parent') {
        return "To apply for Solo Parent services in this system: open the Solo Parent page, log in, complete the application form, provide the needed information and household details, then submit it for review. You can view your submitted form in the system after submission.";
    }

    return "I can guide you on how to apply for AICS or Solo Parent. Which one do you want?";
}

function getStatusReply(string $service): string
{
    if ($service === 'aics') {
        return "For AICS applications, status updates appear in your submitted forms after review. The usual statuses are pending, approved, or declined.";
    }

    if ($service === 'solo_parent') {
        return "For Solo Parent applications, status updates appear in your submitted forms after review. The usual statuses are pending, approved, or declined.";
    }

    return "You can track submitted forms inside your account. If you want, I can explain the status flow for AICS or Solo Parent.";
}

function getDifferenceReply(): string
{
    return "AICS is crisis assistance for urgent needs like medical, funeral, food, shelter, transportation, and similar emergency situations. Solo Parent services are for qualified solo parents who need support, documentation, and program-related assistance specific to their sector.";
}

function getContactReply(): string
{
    return "For office concerns, users may coordinate with the Municipal Social Welfare and Development Office of Cainta through the contact information and office guidance shown in the system. If your question is urgent, it is best to contact or visit the office directly during working hours.";
}

$normalized = normalizeMessage($message);

if ($normalized === '') {
    response(
        "Hello. I can help answer common questions about AICS and Solo Parent services. What would you like to ask?",
        ['AICS requirements', 'Solo Parent requirements', 'How to apply', 'What is the difference?']
    );
}

if (containsAny($normalized, ['reset', 'start over', 'clear'])) {
    $state['service'] = '';
    response(
        "The inquiry assistant has been reset. You can ask about AICS or Solo Parent anytime.",
        ['AICS', 'Solo Parent', 'How to apply', 'Requirements']
    );
}

if (containsAny($normalized, ['hello', 'hi', 'good morning', 'good afternoon', 'good evening', 'hey'])) {
    response(
        "Hello. I can help with AICS and Solo Parent inquiries. You can ask about requirements, eligibility, application steps, status, or contact information.",
        ['AICS', 'Solo Parent', 'Requirements', 'How to apply']
    );
}

if (containsAny($normalized, ['aics'])) {
    $state['service'] = 'aics';
}

if (containsAny($normalized, ['solo parent', 'solo'])) {
    $state['service'] = 'solo_parent';
}

if (containsAny($normalized, ['difference', 'diff', 'compare'])) {
    response(
        getDifferenceReply(),
        ['AICS requirements', 'Solo Parent requirements', 'How to apply for AICS', 'How to apply for Solo Parent']
    );
}

if (containsAny($normalized, ['contact', 'office', 'where', 'location', 'visit'])) {
    response(
        getContactReply(),
        ['AICS requirements', 'Solo Parent requirements', 'Application steps']
    );
}

if (containsAny($normalized, ['status', 'follow up', 'track', 'tracking'])) {
    response(
        getStatusReply($state['service']),
        ['AICS status', 'Solo Parent status', 'How to apply', 'Requirements']
    );
}

if (containsAny($normalized, ['eligibility', 'eligible', 'who can apply', 'qualified', 'qualification'])) {
    if ($state['service'] === '') {
        response(
            "I can explain eligibility for AICS or Solo Parent. Please choose the service first.",
            ['AICS eligibility', 'Solo Parent eligibility']
        );
    }

    $serviceLabel = getServiceLabel($state['service']);
    response(
        getEligibilityReply($state['service']),
        [$serviceLabel . ' requirements', 'How to apply for ' . $serviceLabel, $serviceLabel . ' status']
    );
}

if (containsAny($normalized, ['requirement', 'requirements', 'document', 'documents', 'need to submit'])) {
    if ($state['service'] === '') {
        response(
            "I can give the requirements for AICS or Solo Parent. Please choose one first.",
            ['AICS requirements', 'Solo Parent requirements']
        );
    }

    $serviceLabel = getServiceLabel($state['service']);
    response(
        getRequirementsReply($state['service']),
        ['How to apply for ' . $serviceLabel, $serviceLabel . ' eligibility', $serviceLabel . ' status']
    );
}

if (containsAny($normalized, ['apply', 'application', 'submit', 'how to'])) {
    if ($state['service'] === '') {
        response(
            "I can guide you through the application steps for AICS or Solo Parent. Please choose one first.",
            ['How to apply for AICS', 'How to apply for Solo Parent']
        );
    }

    $serviceLabel = getServiceLabel($state['service']);
    response(
        getApplyReply($state['service']),
        [$serviceLabel . ' requirements', $serviceLabel . ' eligibility', $serviceLabel . ' status']
    );
}

if ($state['service'] === 'aics' && containsAny($normalized, ['what is', 'about', 'info', 'information'])) {
    response(
        "AICS means Assistance to Individuals in Crisis Situation. It is intended for urgent or crisis-related needs such as medical, funeral, educational, food, shelter, transportation, and similar concerns, subject to assessment.",
        ['AICS requirements', 'AICS eligibility', 'How to apply for AICS']
    );
}

if ($state['service'] === 'solo_parent' && containsAny($normalized, ['what is', 'about', 'info', 'information'])) {
    response(
        "The Solo Parent service supports qualified solo parents through system-based application intake, review, and program-related assistance. It helps applicants submit information and track their application in a more organized way.",
        ['Solo Parent requirements', 'Solo Parent eligibility', 'How to apply for Solo Parent']
    );
}

if ($state['service'] !== '') {
    $serviceLabel = getServiceLabel($state['service']);
    response(
        "I understood that you are asking about {$serviceLabel}. You can ask about requirements, eligibility, application steps, or status.",
        [$serviceLabel . ' requirements', $serviceLabel . ' eligibility', 'How to apply for ' . $serviceLabel, $serviceLabel . ' status']
    );
}

response(
    "I can help with AICS and Solo Parent inquiries. Try asking about requirements, eligibility, how to apply, status, or the difference between the two services.",
    ['AICS', 'Solo Parent', 'Requirements', 'How to apply', 'What is the difference?']
);
?>
