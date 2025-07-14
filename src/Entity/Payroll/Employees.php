<?php

namespace PISystems\ExactOnline\Entity\Payroll;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read employees.
 *
 * For more information about the employees functionality in Exact Online, see Working with employees in Exact Online.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PayrollEmployees
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/payroll/Employees", "Employees")]
#[Exact\Method(HttpMethod::GET)]
class Employees extends DataSource
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
     * Obsolete
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ActiveEmployment = null;

    /**
     * Second address lineNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine2 = null;

    /**
     * Third address lineNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine3 = null;

    /**
     * Street of addressNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressStreet = null;

    /**
     * Street number of addressNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressStreetNumber = null;

    /**
     * Street number suffix of addressNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressStreetNumberSuffix = null;

    /**
     * Birth dateNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $BirthDate = null;

    /**
     * Birth name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BirthName = null;

    /**
     * Birth middle name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BirthNamePrefix = null;

    /**
     * Birth placeNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BirthPlace = null;

    /**
     * Email of the employee at the office
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessEmail = null;

    /**
     * Fax number of the employee at the office
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessFax = null;

    /**
     * Office mobile number of the employee
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessMobile = null;

    /**
     * Phone number of the employee at the office
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessPhone = null;

    /**
     * Phone extension of the employee at the office
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessPhoneExtension = null;

    /**
     * Obsolete
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CASONumber = null;

    /**
     * CityNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $City = null;

    /**
     * Code of the employee
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Code = null;

    /**
     * Country codeNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Country = null;

    /**
     * Creation date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * User ID of creator
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * Name of creator
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Customer ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Customer = null;

    /**
     * Custom field endpoint. Provided only for the Exact Online Premium users.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomField = null;

    /**
     * Division code
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * Page: User maintenance page; Section: PersonalE-mail address of a user. If employee is linked to a user, the user email is stored in this property.Note:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employeesUsers created in Exact Online can access the company or companies in an administration.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Email = null;

    /**
     * Employee number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmployeeHID = null;

    /**
     * End date of the employee
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * First name of the employee
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FirstName = null;

    /**
     * Full name of the employee
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FullName = null;

    /**
     * Gender
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Gender = null;

    /**
     * Numeric ID of the employee
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $HID = null;

    /**
     * Initials
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Initials = null;

    /**
     * IsActive
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsActive = null;

    /**
     * Indicates whether the employee is anonymised.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsAnonymised = null;

    /**
     * Language code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Language = null;

    /**
     * Last name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LastName = null;

    /**
     * Description of the location of the employee (where am I?)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LocationDescription = null;

    /**
     * Direct manager of the employeeNote:  The manager must  be in the same division as the employeeThe manager should not  be subordinate of their employee
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Manager = null;

    /**
     * Date of marriageNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $MaritalDate = null;

    /**
     * Marital statusNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $MaritalStatus = null;

    /**
     * Middle name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $MiddleName = null;

    /**
     * Mobile phoneNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Mobile = null;

    /**
     * Last modified date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * User ID of modifier
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * Name of modifier
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * Obsolete
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Municipality = null;

    /**
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $NameComposition = null;

    /**
     * NationalityNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Nationality = null;

    /**
     * Nick name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $NickName = null;

    /**
     * Additional notes
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * Name of partnerNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PartnerName = null;

    /**
     * Middle name of partnerNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PartnerNamePrefix = null;

    /**
     * Reference to the persons table in which the personal data of the employee is stored
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Person = null;

    /**
     * Phone numberNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Phone = null;

    /**
     * Phone number extensionNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PhoneExtension = null;

    /**
     * Bytes of the logo image
     *
     *
     * @var null|string Binary data, warning, do not pre-encode this data.
     */
    #[EDM\Binary]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Picture = null;

    /**
     * Filename of picture
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureFileName = null;

    /**
     * Thumbnail url of the picture
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureThumbnailUrl = null;

    /**
     * Url of picture
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureUrl = null;

    /**
     * PostcodeNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Postcode = null;

    /**
     * Section: Personal Personal e-mail address of the employee.Note:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PrivateEmail = null;

    /**
     * Social security numberNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SocialSecurityNumber = null;

    /**
     * Start date of the employee
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * StateNote:  The value is only returned if user has any of the following roles: View userEnter variable payroll mutationsManage employeesAnonymise employee and userView personal information Of employees
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $State = null;

    /**
     * Title
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Title = null;

    /**
     * User ID of employee
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $User = null;

    /**
     * Name of user
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UserFullName = null;
}