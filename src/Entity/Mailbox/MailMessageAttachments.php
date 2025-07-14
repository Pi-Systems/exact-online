<?php

namespace PISystems\ExactOnline\Entity\Mailbox;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get information related to a mail message attachment or add an attachment to a mail message.
 *
 * Important notes:To use this endpoint, the Mailbox feature set is required in the license.
 *
 * This entity supports webhooks.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=MailboxMailMessageAttachments
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/mailbox/MailMessageAttachments", "MailMessageAttachments")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
class MailMessageAttachments extends DataSource
{

    /**
     * Primary key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ID = null;

    /**
     * For performance reasons Attachment is Write-Only. The blob can be downloaded using the supplied Url
     *
     *
     * @var null|string Binary data, warning, do not pre-encode this data.
     */
    #[EDM\Binary]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Attachment = null;

    /**
     * File extension of attachment
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AttachmentFileExtension = null;

    /**
     * File name of attachment
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $AttachmentFileName = null;

    /**
     * File size
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $FileSize = null;

    /**
     * Reference to Mail message
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $MailMessageID = null;

    /**
     * Indicates the SourceEntry of Scanning service
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $MailMessageOrigin = null;

    /**
     * Reference to recipient account
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RecipientAccount = null;

    /**
     * Reference to sender account
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SenderAccount = null;

    /**
     * Type of mail message attachment     * 0-Unknown     * 10-PDF     * 11-XML (ZUGFeRD)     * 20-UBL 2.0     * 21-SIB     * 22-Simplerinvoicing 1.0     * 23-PEPPOL     * 24-eFFF     * 25-Simplerinvoicing 1.1     * 26-Simplerinvoicing 1.2     * 30-Finvoice     * 40-eInvoice     * 50-XML     * 60-XBRL     * 70-Bank import     * 71-Internal bank statement (TestAutomation)     * 72-XLSM     * 73-BankGateway source     * 80-Bank export     * 81-Direct debit     * 82-Mandates     * 85-Message     * 86-Status update     * 90-Statistics     * 91-Statistics Json     * 100-TXT     * 110-Soda     * 120-OfficialReturnProof     * 130-UBL 2.1
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Type = null;

    /**
     * Description of Type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TypeDescription = null;

    /**
     * To get the file in its original format (xml, jpg, pdf, etc.) append &Download=1 to the url.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Url = null;
}