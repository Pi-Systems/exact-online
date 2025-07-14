<?php

namespace PISystems\ExactOnline\Entity\Mailbox;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get information related to a mail message or create a mail message.
 *
 * Important notes:To use this endpoint, the Mailbox feature set is required in the license.
 *
 * This entity supports webhooks.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=MailboxMailMessagesSent
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/mailbox/MailMessagesSent", "MailMessagesSent")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
class MailMessagesSent extends DataSource
{

    /**
     * The primary key of the mail message.
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
     * Bank to/from which the mail message is sent/received. This is only used for mail messages of type 'Bank'. It has an attachment containing the bank file.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Bank = null;

    /**
     * Bank account for which the mail message is sent. This is only used for mail messages of type 'Bank'. It has an attachment containing the bank export file.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $BankAccount = null;

    /**
     * Country code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Country = null;

    /**
     * The date and time on which the mail message was created.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * The user ID of the creator of the mail message.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * The name of the creator of the mail message.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Administration from which the mail message is sent. This is only used for mail messages of type 'Bank'.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ForDivision = null;

    /**
     * The date and time the mail message was last modified.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * The ID of the user that last modified the mail message.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * The name of the user that last modified the mail message.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * Specifies the operation upon dealing with the mailmessage (Kirean scan service).Operation can have the following values: 1= Purchase invoice without details2= Purchase invoice with details3= Sales invoice4= Bank statement
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Operation = null;

    /**
     * Provides a link to another mail message (Kirean scan service).
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $OriginalMessage = null;

    /**
     * The subject of the original message.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $OriginalMessageSubject = null;

    /**
     * The key of the application that created the message. It is filled with a fixed value when created from within Exact Online.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PartnerKey = null;

    /**
     * The number of lines of the returned mail message attachment (Kirean scan service).
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $Quantity = null;

    /**
     * Reference to the account that is receiving this mail message.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RecipientAccount = null;

    /**
     * Indicates whether the recipient deleted this message. If this is the case the recipient can't see it anymore and the sender can actually delete it.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $RecipientDeleted = null;

    /**
     * The mailbox address of the recipient.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RecipientMailbox = null;

    /**
     * The description of the mailbox of the recipient.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RecipientMailboxDescription = null;

    /**
     * The mailbox ID of the recipient. The owner of this mailbox will see the message in the inbox.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $RecipientMailboxID = null;

    /**
     * The status of the mail message, only the recipient can update this. RecipientStatus can have the following values: 5= Rejected42= In process10= Draft43= At the scanning service20= Open45= Error during processing25= Prepared46= Blocked30= Approved50= Processed40= Realized
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $RecipientStatus = null;

    /**
     * The description of the recipient status in English.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RecipientStatusDescription = null;

    /**
     * Reference to the account of the sender.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SenderAccount = null;

    /**
     * Date the message was sent. By default this is the date the message is created. It can be an earlier date when the mail message is imported from xml (the date the xml was sent).
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $SenderDateSent = null;

    /**
     * Indicates whether the sender deleted the message. This means the sender can't see it anymore and the recipient can actually delete it.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $SenderDeleted = null;

    /**
     * The IP address of the sender.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SenderIPAddress = null;

    /**
     * The mailbox address of the sender. The owner of this mailbox will see the message in the sent items.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SenderMailbox = null;

    /**
     * The description of the sender mailbox.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SenderMailboxDescription = null;

    /**
     * The mailbox ID of the sender. The owner of this mailbox will see the message in the sent items.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SenderMailboxID = null;

    /**
     * Skip Recipient MailBoxAddress Override for Scanning service
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SkipRecipientMailBoxAddressOverride = null;

    /**
     * The subject of the mail message.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Subject = null;

    /**
     * Provides a link between Exact Online and the banks.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SynchronizationCode = null;

    /**
     * The mail message type can have the following values: 0= Other5000= Invitation1000= Purchase invoice5010= Invitation accepted1010= Reminder5020= Invitation rejected1020= Quotation6100= Annual statement1030= Sales order6200= Income tax1040= Delivery note6210= Corporate tax1050= Return note6220= VAT Return XBRL1060= Purchase order6221= Supplementary VAT Return XBRL1100= Sales invoice6230= EU Sales list XBRL1200= CRM document6300= Credit report1300= Soda6400= TaxKitDiese2000= Bank7000= Scanned document3000= VAT Return8000= Tax Declaration3001= Postpone submission8010= Payroll tax declaration XBRL4000= EC Sales list8020= Yearly payroll tax declaration XBRL
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Type = null;
}