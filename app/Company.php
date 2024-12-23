<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Company extends Model
{
    use SoftDeletes;

    public $table = 'company';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user',
        'name',
        'address',
        'phone',
        'VAT',
        'description',
        'legal_form',
        'sector_activity',
        'company_size',
        'operational_address',
        'contact_person_name',
        'phone_code'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static $compay_legal_form =
    [
        'AT' => [
            'Einzelunternehmen: A sole proprietorship.',
            'Offene Gesellschaft (OG): A general partnership.',
            'Kommanditgesellschaft (KG): A limited partnership.',
            'Gesellschaft mit beschränkter Haftung (GmbH): A private limited company.',
            'Aktiengesellschaft (AG): A public limited company.',
        ],
        'BE' => [
            'Entreprise Individuelle: A sole proprietorship.',
            'Société en Nom Collectif (SNC): A general partnership.',
            'Société Privée à Responsabilité Limitée (SPRL): A private limited liability company.',
            'Société Anonyme (SA): A public limited company.',
            'Société Coopérative (SC): A cooperative company.',
        ],
        'BG' => [
            'Sole Trader: A sole proprietorship.',
            'General Partnership: A general partnership.',
            'Limited Partnership: A limited partnership.',
            'Limited Liability Company (OOD): A private limited liability company.',
            'Joint Stock Company (AD): A public limited company.',
        ],
        'HR' => [
            'Obrt: A sole proprietorship.',
            'Javno Trgovačko Društvo (JTD): A general partnership.',
            'Komanditno Društvo (KD): A limited partnership.',
            'Društvo s Ograničenom Odgovornošću (d.o.o.): A private limited company.',
            'Dioničko Društvo (d.d.): A public limited company.',
        ],
        'CY' => [
            'Sole Proprietor: A sole proprietorship.',
            'General Partnership: A general partnership.',
            'Limited Partnership: A limited partnership.',
            'Private Company Limited by Shares (Ltd): A private limited company.',
            'Public Company Limited by Shares: A public limited company.',
        ],
        'CZ' => [
            'Osoba Samostatně Výdělečně Činná (OSVČ): A sole proprietorship.',
            'Veřejná Obchodní Společnost (VOS): A general partnership.',
            'Komanditní Společnost (KS): A limited partnership.',
            'Společnost s Ručením Omezeným (s.r.o.): A private limited company.',
            'Akciová Společnost (a.s.): A public limited company.',
        ],
        'DK' => [
            'Enkeltmandsvirksomhed: A sole proprietorship.',
            'Interessentskab (I/S): A general partnership.',
            'Kommanditselskab (K/S): A limited partnership.',
            'Aktieselskab (A/S): A public limited liability company.',
            'Anpartsselskab (ApS): A private limited liability company.',
        ],
        'FI' => [
            'Toiminimi: A sole proprietorship.',
            'Avoin Yhtiö (Ay): A general partnership.',
            'Kommandiittiyhtiö (Ky): A limited partnership.',
            'Osakeyhtiö (Oy): A private limited liability company.',
            'Julkinen Osakeyhtiö (Oyj): A public limited liability company.',
        ],
        'FR' => [
            'Entreprise Individuelle (EI): A sole proprietorship.',
            'Micro-entrepreneur: A simplified sole proprietorship for small businesses.',
            'Société à Responsabilité Limitée (SARL): A private limited company.',
            'Société par Actions Simplifiée (SAS): A simplified joint-stock company.',
            'Société Anonyme (SA): A public limited company.'
        ],
        'DE' => [
            'Einzelunternehmen: A sole proprietorship.',
            'Gesellschaft mit Beschränkter Haftung (GmbH): A private limited liability company.',
            'Unternehmergesellschaft (haftungsbeschränkt) (UG): A simplified form of GmbH with lower capital requirements.',
            'Aktiengesellschaft (AG): A public limited company.',
            'Kommanditgesellschaft (KG): A limited partnership.'
        ],
        'LT' => [
            'Individuali Įmonė: A sole proprietorship.',
            'Uždarosios Akcinės Bendrovės (UAB): A private limited liability company.',
            'Akcinė Bendrovė (AB): A public limited liability company.',
            'Mažoji Bendrija (MB): A small partnership.',
            'Tikroji Ūkinė Bendrija (TŪB): A general partnership.',
            'Komanditinė Ūkinė Bendrija (KŪB): A limited partnership.',
        ],
        'IT' => [
            'Ditta Individuale: A sole proprietorship.',
            'Società a Responsabilità Limitata (SRL): A private limited company.',
            'SRL Semplificata (SRLS): A simplified form of SRL.',
            'Società per Azioni (SPA): A public limited company.',
            'Società in Nome Collettivo (SNC): A general partnership.',
            'Società in Accomandita Semplice (SAS): A limited partnership.'
        ],
        'NL' => [
            'Eenmanszaak: A sole proprietorship.',
            'Besloten Vennootschap (BV): A private limited liability company.',
            'Naamloze Vennootschap (NV): A public limited liability company.',
            'Vennootschap onder Firma (VOF): A general partnership.',
            'Commanditaire Vennootschap (CV): A limited partnership.',
        ],
        'SE' => [
            'Enskild Firma: A sole proprietorship.',
            'Handelsbolag (HB): A general partnership.',
            'Kommanditbolag (KB): A limited partnership.',
            'Aktiebolag (AB): A private limited liability company.',
            'Publikt Aktiebolag (Publ AB): A public limited liability company.',
        ],
        'ES' => [
            'Empresa Individual: A sole proprietorship.',
            'Sociedad Limitada (SL): A private limited company.',
            'Sociedad Anónima (SA): A public limited company.',
            'Sociedad Colectiva: A general partnership.',
            'Sociedad Comanditaria: A limited partnership.',
        ],
        'GB' => [
            'Sole Trader: A business owned and operated by one individual.',
            'Partnership: A business owned by two or more individuals.',
            'Limited Liability Partnership (LLP): A partnership with limited liability for its members.',
            'Private Limited Company (Ltd): A company with limited liability and shares not available to the public.',
            'Public Limited Company (PLC): A company with limited liability and publicly traded shares.'
        ],
    ];

    public static $sectors_of_activity = [
        [
            'name' => "Agriculture, forestry, and fishing",
            'desc' => "Crop cultivation, Animal husbandry, Forestry and logging, Fishing and aquaculture"
        ],
        [
            'name' => "Mining and quarrying",
            'desc' => "Coal and lignite mining, Extraction of crude petroleum and natural gas, Mining of metal ores, Other mining activities"
        ],
        [
            'name' => "Manufacturing",
            'desc' => "Food and beverage manufacturing, Textile and clothing industry, Machinery and equipment manufacturing, Vehicle and transport equipment manufacturing, Chemical, pharmaceutical, and plastic production, Metal product manufacturing"
        ],
        [
            'name' => "Electricity, gas, steam, and air conditioning supply",
            'desc' => "Electricity generation and distribution, Natural gas distribution, Steam and air conditioning supply"
        ],
        [
            'name' => "Water supply; sewerage, waste management, and remediation",
            'desc' => "Water supply and sanitation, Waste treatment and recycling, Environmental remediation and cleanup activities"
        ],
        [
            'name' => "Construction",
            'desc' => "Building construction, Civil engineering, Specialized construction activities (e.g., installations)"
        ],
        [
            'name' => "Wholesale and retail trade; repair of motor vehicles",
            'desc' => "Wholesale trade and brokerage, Retail trade of various goods, Repair of motor vehicles and motorcycles"
        ],
        [
            'name' => "Transportation and storage",
            'desc' => "Land transportation, Sea transportation, Air transportation, Warehousing and logistics, Transportation support activities"
        ],
        [
            'name' => "Accommodation and food service activities",
            'desc' => "Hotels, campsites, and other accommodation, Food services and catering"
        ],
        [
            'name' => "Information and communication",
            'desc' => "Publishing and multimedia production, Telecommunications, Software development and programming, IT consultancy and services, Data processing, hosting, and related activities, Web portal operation, Computer systems design and integration"
        ],
        [
            'name' => "Financial and insurance activities",
            'desc' => "Banking and financial services, Insurance and pension funds, Asset management"
        ],
        [
            'name' => "Real estate activities",
            'desc' => "Real estate sales, Real estate rental, Property management"
        ],
        [
            'name' => "Professional, scientific, and technical activities",
            'desc' => "Legal services, Accounting services, Consulting services, Scientific research and development, Architectural services, Engineering services"
        ],
        [
            'name' => "Public administration and defense; compulsory social security",
            'desc' => "Government administration, National defense, Public security"
        ],
        [
            'name' => "Education",
            'desc' => "Primary education, Secondary education, Higher education, Vocational training"
        ],
        [
            'name' => "Human health and social work activities",
            'desc' => "Hospital services, Residential care activities, Outpatient healthcare services"
        ],
        [
            'name' => "Arts, entertainment, and recreation",
            'desc' => "Creative and artistic activities, Performance activities, Sports activities, Recreational activities, Museum management"
        ],
        [
            'name' => "Other service activities",
            'desc' => "Personal services (e.g., hairdressing, laundry), Membership organization activities, Repair of personal and household goods"
        ],
    ];    

    public static $company_sizes = [
        '1 ~ 9',
        '10 ~ 50',
        '51 ~ 100',
        '101 ~ 500',
        '+ 501'
    ];
}
