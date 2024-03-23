<?php

namespace App\Library;

class DigitalSignature
{
  /**
   * Get digital certificate contents
   *
   * @param String $cert Base64 Encoded
   * @return Array Empty if operation fails
   */
  public static function getContents(string $cert): array {

      # Original BASE64 User certificate provided urlencoded from eauth API
      $holderData = openssl_x509_parse(urldecode($cert));

    return $holderData ?: [];
  }

  /**
   * Get subject identifier field for a specific signature contents
   * Either EGN/EIK
   *
   * @param array $cerContents Parsed signature contents
   * @return array Contains single item with identifier field as key (eik/egn) and its contents as value
   */
  public static function getSubjectIdentifier(array $certContents): array {

    $identifier = [];

    if (empty($certContents) || !isset($certContents['subject'])) {
      return [];
    }

    # Identifier Subject Key and its code contained in value
    $identifierKeys = [
      'organizationIdentifier' => 'NTRBG',
      'serialNumber'           => 'PNOBG'
    ];

    $subject  = $certContents['subject'];

    foreach ($identifierKeys as $subjectKey => $code) {

      if (isset($subject[$subjectKey]) && strpos($subject[$subjectKey], $code) !== false) {

        $fieldName = $code == 'NTRBG' ? 'eik' : 'egn';
        $identifier['field'] = $fieldName;
        $identifier['value'] = explode('-', $subject[$subjectKey])[1];
        break;

      }
    }

    return $identifier;
  }
}
