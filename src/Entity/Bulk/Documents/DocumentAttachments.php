<?php

namespace PISystems\ExactOnline\Entity\Bulk\Documents;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * To be able to upload an attachment (i.e. a PDF file) you first need to create a (empty) document.
 * This bulk service has a page size of 1000.
 *
 *
 * Only the following filters are supported for this endpoint:
 *
 * Document, ID
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=BulkDocumentsDocumentAttachments
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/bulk/Documents/DocumentAttachments", "Documents/DocumentAttachments")]
#[Exact\Method(HttpMethod::GET)]
class DocumentAttachments extends DataSource
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
    public null|string $ID = null;

    /**
     * Contains the attachment(Format: Base64 encoded) **For the Bulk endpoint, Attachment will be always null**
     *
     *
     * @var null|string Binary data, warning, do not pre-encode this data.
     */
    #[EDM\Binary]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Attachment = null;

    /**
     * Reference to the Document
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Document = null;

    /**
     * Filename of the attachment
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FileName = null;

    /**
     * File size of the attachment
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FileSize = null;

    /**
     * Url of the attachment. To get the file in its original format (xml, jpg, pdf, etc.) append &Download=1 to the url.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Url = null;
}