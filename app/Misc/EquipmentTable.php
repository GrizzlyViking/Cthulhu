<?php

namespace App\Misc;

/**
 * The Investigator Handbook's 1920s equipment lists (pp. 238–246), transcribed.
 *
 * Only what an investigator would carry: things that go in a suitcase, a
 * travel chest, or a coat pocket. The book's other columns on those pages —
 * food and drink, meals, lodging, real estate, furniture, household goods,
 * vehicles and their accessories, and every kind of fare and tuition — are
 * deliberately absent, as are a handful of immovable items inside the sections
 * we do keep (the floor safe, the wheelchair, the parlor organ).
 *
 * The melee weapons printed on p.246 are absent too: they already live in
 * {@see WeaponTable} with damage statistics and their own cost cell, and one
 * dagger is enough.
 *
 * `cost` is the book's price cell verbatim, including ranges ("$1.35-$2.25"),
 * cent signs and per-unit notes ("9¢/lb."), because the prices are only ever
 * shown to a player deciding what to buy — never stored against what they own.
 *
 * Both EquipmentSeeder and the sync migration read from here, so add items to
 * this table rather than to the seeder.
 */
class EquipmentTable
{
    public const MENS_CLOTHING = "Men's Clothing";

    public const WOMENS_CLOTHING = "Women's Clothing";

    public const PERSONAL_CARE = 'Personal Care';

    public const MEDICAL = 'Medical Equipment';

    public const OUTDOOR = 'Outdoor & Travel Gear';

    public const LUGGAGE = 'Luggage';

    public const TENTS = 'Tents & Camp';

    public const TOOLS = 'Tools';

    public const INVESTIGATOR = 'Investigator Tools';

    public const COMMUNICATIONS = 'Communications';

    public const ENTERTAINMENT = 'Entertainment';

    public const SPORTS = 'Sports & Games';

    public const AMMUNITION = 'Ammunition';

    /**
     * The sections in the order the book prints them, which is the order the
     * pickers group by.
     *
     * @return array<int, string>
     */
    public static function sections(): array
    {
        return [
            self::MENS_CLOTHING,
            self::WOMENS_CLOTHING,
            self::PERSONAL_CARE,
            self::MEDICAL,
            self::OUTDOOR,
            self::LUGGAGE,
            self::TENTS,
            self::TOOLS,
            self::INVESTIGATOR,
            self::COMMUNICATIONS,
            self::ENTERTAINMENT,
            self::SPORTS,
            self::AMMUNITION,
        ];
    }

    /**
     * Every catalogue item, keyed by nothing — the slug inside each row is the
     * stable identifier the seeder and migration match on.
     *
     * @return array<int, array{slug: string, name: string, section: string, cost: string}>
     */
    public static function all(): array
    {
        return [
            ['slug' => 'men-s-clothing--worsted-wool-dress-suit',                 'name' => 'Worsted Wool Dress Suit',                               'section' => self::MENS_CLOTHING,       'cost' => '$17.95'],
            ['slug' => 'men-s-clothing--cashmere-dress-suit',                     'name' => 'Cashmere Dress Suit',                                   'section' => self::MENS_CLOTHING,       'cost' => '$18.50'],
            ['slug' => 'men-s-clothing--suit-mohair',                             'name' => 'Suit, mohair',                                          'section' => self::MENS_CLOTHING,       'cost' => '$13.85'],
            ['slug' => 'men-s-clothing--corduroy-norfolk-suit',                   'name' => 'Corduroy Norfolk Suit',                                 'section' => self::MENS_CLOTHING,       'cost' => '$9.95'],
            ['slug' => 'men-s-clothing--union-suit-forest-mills',                 'name' => 'Union suit, Forest Mills',                              'section' => self::MENS_CLOTHING,       'cost' => '69¢'],
            ['slug' => 'men-s-clothing--outdoor-coat',                            'name' => 'Outdoor Coat',                                          'section' => self::MENS_CLOTHING,       'cost' => '$9.95-$35.00'],
            ['slug' => 'men-s-clothing--dog-fur-overcoat',                        'name' => 'Dog Fur Overcoat',                                      'section' => self::MENS_CLOTHING,       'cost' => '$37.50'],
            ['slug' => 'men-s-clothing--chesterfield-overcoat',                   'name' => 'Chesterfield Overcoat',                                 'section' => self::MENS_CLOTHING,       'cost' => '$19.95'],
            ['slug' => 'men-s-clothing--oxford-dress-shoes',                      'name' => 'Oxford Dress Shoes',                                    'section' => self::MENS_CLOTHING,       'cost' => '$6.95'],
            ['slug' => 'men-s-clothing--leather-work-shoes',                      'name' => 'Leather Work Shoes',                                    'section' => self::MENS_CLOTHING,       'cost' => '$4.95'],
            ['slug' => 'men-s-clothing--slacks-white-flannel',                    'name' => 'Slacks, white flannel',                                 'section' => self::MENS_CLOTHING,       'cost' => '$8.00'],
            ['slug' => 'men-s-clothing--lace-bottom-breeches',                    'name' => 'Lace-bottom Breeches',                                  'section' => self::MENS_CLOTHING,       'cost' => '$4.95'],
            ['slug' => 'men-s-clothing--shirt-percale',                           'name' => 'Shirt, percale',                                        'section' => self::MENS_CLOTHING,       'cost' => '79¢ -$1.25'],
            ['slug' => 'men-s-clothing--broadcloth-dress-shirt',                  'name' => 'Broadcloth Dress Shirt',                                'section' => self::MENS_CLOTHING,       'cost' => '$1.95'],
            ['slug' => 'men-s-clothing--shaker-sweater',                          'name' => 'Shaker Sweater',                                        'section' => self::MENS_CLOTHING,       'cost' => '$7.69'],
            ['slug' => 'men-s-clothing--felt-fedora',                             'name' => 'Felt Fedora',                                           'section' => self::MENS_CLOTHING,       'cost' => '$4.95'],
            ['slug' => 'men-s-clothing--wool-golf-cap',                           'name' => 'Wool Golf Cap',                                         'section' => self::MENS_CLOTHING,       'cost' => '79¢'],
            ['slug' => 'men-s-clothing--straw-hat',                               'name' => 'Straw Hat',                                             'section' => self::MENS_CLOTHING,       'cost' => '$1.95'],
            ['slug' => 'men-s-clothing--padded-leather-football-helmet',          'name' => 'Padded Leather Football Helmet',                        'section' => self::MENS_CLOTHING,       'cost' => '$3.65'],
            ['slug' => 'men-s-clothing--sweatshirt',                              'name' => 'Sweatshirt',                                            'section' => self::MENS_CLOTHING,       'cost' => '98¢'],
            ['slug' => 'men-s-clothing--sealskin-fur-cap',                        'name' => 'Sealskin Fur Cap',                                      'section' => self::MENS_CLOTHING,       'cost' => '$16.95'],
            ['slug' => 'men-s-clothing--silk-four-in-hand-tie',                   'name' => 'Silk Four-in-hand Tie',                                 'section' => self::MENS_CLOTHING,       'cost' => '96¢'],
            ['slug' => 'men-s-clothing--necktie-silk',                            'name' => 'Necktie, silk',                                         'section' => self::MENS_CLOTHING,       'cost' => '50¢'],
            ['slug' => 'men-s-clothing--batwing-bow-tie',                         'name' => 'Batwing Bow Tie',                                       'section' => self::MENS_CLOTHING,       'cost' => '55¢'],
            ['slug' => 'men-s-clothing--sock-garters',                            'name' => 'Sock Garters',                                          'section' => self::MENS_CLOTHING,       'cost' => '39¢'],
            ['slug' => 'men-s-clothing--cotton-union-suit',                       'name' => 'Cotton Union Suit',                                     'section' => self::MENS_CLOTHING,       'cost' => '$1.48'],
            ['slug' => 'men-s-clothing--cufflinks',                               'name' => 'Cufflinks',                                             'section' => self::MENS_CLOTHING,       'cost' => '40¢'],
            ['slug' => 'men-s-clothing--leather-belt',                            'name' => 'Leather Belt',                                          'section' => self::MENS_CLOTHING,       'cost' => '$1.35'],
            ['slug' => 'men-s-clothing--suspenders',                              'name' => 'Suspenders',                                            'section' => self::MENS_CLOTHING,       'cost' => '79¢'],
            ['slug' => 'men-s-clothing--hiking-boots',                            'name' => 'Hiking Boots',                                          'section' => self::MENS_CLOTHING,       'cost' => '$7.25'],
            ['slug' => 'men-s-clothing--shoes-with-cleats',                       'name' => 'Shoes with cleats',                                     'section' => self::MENS_CLOTHING,       'cost' => '$5.25'],
            ['slug' => 'men-s-clothing--bathing-suit',                            'name' => 'Bathing Suit',                                          'section' => self::MENS_CLOTHING,       'cost' => '$3.45'],
            ['slug' => 'men-s-clothing--canvas-bathing-shoes',                    'name' => 'Canvas Bathing Shoes',                                  'section' => self::MENS_CLOTHING,       'cost' => '75¢'],
            ['slug' => 'women-s-clothing--chic-designer-dress',                   'name' => 'Chic Designer Dress',                                   'section' => self::WOMENS_CLOTHING,     'cost' => '$90.00+'],
            ['slug' => 'women-s-clothing--silk-crepe-dress',                      'name' => 'Silk Crepe Dress',                                      'section' => self::WOMENS_CLOTHING,     'cost' => '$13.95'],
            ['slug' => 'women-s-clothing--silk-taffeta-dress',                    'name' => 'Silk Taffeta Dress',                                    'section' => self::WOMENS_CLOTHING,     'cost' => '$10.95'],
            ['slug' => 'women-s-clothing--satin-charmeuse',                       'name' => 'Satin Charmeuse',                                       'section' => self::WOMENS_CLOTHING,     'cost' => '$10.95'],
            ['slug' => 'women-s-clothing--gingham-dress',                         'name' => 'Gingham Dress',                                         'section' => self::WOMENS_CLOTHING,     'cost' => '$2.59'],
            ['slug' => 'women-s-clothing--french-repp-dress',                     'name' => 'French Repp Dress',                                     'section' => self::WOMENS_CLOTHING,     'cost' => '$10.95'],
            ['slug' => 'women-s-clothing--pleated-skirt-silk',                    'name' => 'Pleated Skirt, silk',                                   'section' => self::WOMENS_CLOTHING,     'cost' => '$7.95'],
            ['slug' => 'women-s-clothing--blouse-cotton',                         'name' => 'Blouse, cotton',                                        'section' => self::WOMENS_CLOTHING,     'cost' => '$1.98'],
            ['slug' => 'women-s-clothing--worsted-wool-sweater',                  'name' => 'Worsted Wool Sweater',                                  'section' => self::WOMENS_CLOTHING,     'cost' => '$9.48'],
            ['slug' => 'women-s-clothing--cotton-crepe-negligee',                 'name' => 'Cotton Crepe Negligee',                                 'section' => self::WOMENS_CLOTHING,     'cost' => '88¢'],
            ['slug' => 'women-s-clothing--spike-heeled-parisian-shoes',           'name' => 'Spike-heeled Parisian Shoes',                           'section' => self::WOMENS_CLOTHING,     'cost' => '$4.45'],
            ['slug' => 'women-s-clothing--leather-one-strap-slippers',            'name' => 'Leather One-strap Slippers',                            'section' => self::WOMENS_CLOTHING,     'cost' => '98¢'],
            ['slug' => 'women-s-clothing--snug-velour-hat',                       'name' => 'Snug Velour Hat',                                       'section' => self::WOMENS_CLOTHING,     'cost' => '$4.44'],
            ['slug' => 'women-s-clothing--satin-turban-style-hat',                'name' => 'Satin Turban-style Hat',                                'section' => self::WOMENS_CLOTHING,     'cost' => '$3.69'],
            ['slug' => 'women-s-clothing--rayon-elastic-corset',                  'name' => 'Rayon Elastic Corset',                                  'section' => self::WOMENS_CLOTHING,     'cost' => '$2.59'],
            ['slug' => 'women-s-clothing--embroidered-costume-slip',              'name' => 'Embroidered Costume Slip',                              'section' => self::WOMENS_CLOTHING,     'cost' => '$1.59'],
            ['slug' => 'women-s-clothing--silk-hose-3-pairs',                     'name' => 'Silk Hose (3 pairs)',                                   'section' => self::WOMENS_CLOTHING,     'cost' => '$2.25'],
            ['slug' => 'women-s-clothing--bloomers-silk',                         'name' => 'Bloomers, silk',                                        'section' => self::WOMENS_CLOTHING,     'cost' => '$3.98-$4.98'],
            ['slug' => 'women-s-clothing--tweed-jacket-fully-lined',              'name' => 'Tweed Jacket, fully lined',                             'section' => self::WOMENS_CLOTHING,     'cost' => '$3.95'],
            ['slug' => 'women-s-clothing--velour-coat-with-fur-trim',             'name' => 'Velour Coat with Fur Trim',                             'section' => self::WOMENS_CLOTHING,     'cost' => '$39.75'],
            ['slug' => 'women-s-clothing--brown-fox-fur-coat',                    'name' => 'Brown Fox Fur Coat',                                    'section' => self::WOMENS_CLOTHING,     'cost' => '$198.00'],
            ['slug' => 'women-s-clothing--belted-rain-coat-cotton',               'name' => 'Belted Rain Coat, cotton',                              'section' => self::WOMENS_CLOTHING,     'cost' => '$3.98'],
            ['slug' => 'women-s-clothing--belted-rain-coat-schappe-silk',         'name' => 'Belted Rain Coat, Schappe silk',                        'section' => self::WOMENS_CLOTHING,     'cost' => '$8.98'],
            ['slug' => 'women-s-clothing--silk-handbag',                          'name' => 'Silk Handbag',                                          'section' => self::WOMENS_CLOTHING,     'cost' => '$4.98'],
            ['slug' => 'women-s-clothing--dress-hair-comb',                       'name' => 'Dress Hair Comb',                                       'section' => self::WOMENS_CLOTHING,     'cost' => '98¢'],
            ['slug' => 'women-s-clothing--outdoor-shirt-khaki-jean-material',     'name' => 'Outdoor Shirt, Khaki Jean Material',                    'section' => self::WOMENS_CLOTHING,     'cost' => '$1.79'],
            ['slug' => 'women-s-clothing--outdoor-shirt-all-wool-tweed-or-linen', 'name' => 'Outdoor Shirt, All Wool Tweed or Linen',                'section' => self::WOMENS_CLOTHING,     'cost' => '$2.98'],
            ['slug' => 'women-s-clothing--outdoor-knickers-khaki-jean-material',  'name' => 'Outdoor Knickers, Khaki Jean Material',                 'section' => self::WOMENS_CLOTHING,     'cost' => '$1.79'],
            ['slug' => 'women-s-clothing--outdoor-knickers-grey-wool-tweed',      'name' => 'Outdoor Knickers, Grey Wool Tweed',                     'section' => self::WOMENS_CLOTHING,     'cost' => '$2.98'],
            ['slug' => 'women-s-clothing--outdoor-knickers-white-linen',          'name' => 'Outdoor Knickers, White Linen',                         'section' => self::WOMENS_CLOTHING,     'cost' => '$2.98'],
            ['slug' => 'women-s-clothing--khaki-leggings-ankle-to-knee',          'name' => 'Khaki Leggings (Ankle to knee)',                        'section' => self::WOMENS_CLOTHING,     'cost' => '98¢'],
            ['slug' => 'women-s-clothing--outdoor-boots',                         'name' => 'Outdoor Boots',                                         'section' => self::WOMENS_CLOTHING,     'cost' => '$2.59'],
            ['slug' => 'women-s-clothing--bathing-suit',                          'name' => 'Bathing Suit',                                          'section' => self::WOMENS_CLOTHING,     'cost' => '$4.95'],
            ['slug' => 'women-s-clothing--bathing-cap',                           'name' => 'Bathing Cap',                                           'section' => self::WOMENS_CLOTHING,     'cost' => '40¢'],
            ['slug' => 'women-s-clothing--canvas-bathing-shoes',                  'name' => 'Canvas Bathing Shoes',                                  'section' => self::WOMENS_CLOTHING,     'cost' => '54¢'],
            ['slug' => 'women-s-clothing--shoes-pumps',                           'name' => 'Shoes, Pumps',                                          'section' => self::WOMENS_CLOTHING,     'cost' => '$1.29'],
            ['slug' => 'personal-care--make-up-kit',                              'name' => 'Make-up Kit',                                           'section' => self::PERSONAL_CARE,       'cost' => '$4.98'],
            ['slug' => 'personal-care--men-s-toilet-set-10-pieces',               'name' => 'Men\'s Toilet Set (10 pieces)',                         'section' => self::PERSONAL_CARE,       'cost' => '$9.98'],
            ['slug' => 'personal-care--women-s-toilet-set-15-pieces',             'name' => 'Women\'s Toilet Set (15 pieces)',                       'section' => self::PERSONAL_CARE,       'cost' => '$22.95'],
            ['slug' => 'personal-care--hair-colorator',                           'name' => 'Hair Colorator',                                        'section' => self::PERSONAL_CARE,       'cost' => '79¢'],
            ['slug' => 'personal-care--curling-iron-wavette',                     'name' => 'Curling Iron, Wavette',                                 'section' => self::PERSONAL_CARE,       'cost' => '$2.19'],
            ['slug' => 'personal-care--hair-brush',                               'name' => 'Hair Brush',                                            'section' => self::PERSONAL_CARE,       'cost' => '89¢'],
            ['slug' => 'personal-care--hair-net-zephyr-4',                        'name' => 'Hair net, Zephyr (4)',                                  'section' => self::PERSONAL_CARE,       'cost' => '25¢'],
            ['slug' => 'personal-care--mouthwash-listerine',                      'name' => 'Mouthwash, Listerine',                                  'section' => self::PERSONAL_CARE,       'cost' => '79¢'],
            ['slug' => 'personal-care--shampoo-coconut-oil',                      'name' => 'Shampoo, Coconut Oil',                                  'section' => self::PERSONAL_CARE,       'cost' => '50¢'],
            ['slug' => 'personal-care--soap-12-cakes',                            'name' => 'Soap (12 Cakes)',                                       'section' => self::PERSONAL_CARE,       'cost' => '$1.39'],
            ['slug' => 'personal-care--talcum-powder',                            'name' => 'Talcum powder',                                         'section' => self::PERSONAL_CARE,       'cost' => '19¢'],
            ['slug' => 'personal-care--toothpaste-pepsodent',                     'name' => 'Toothpaste, Pepsodent',                                 'section' => self::PERSONAL_CARE,       'cost' => '39¢'],
            ['slug' => 'medical-equipment--aspirin-12-pills',                     'name' => 'Aspirin (12 pills)',                                    'section' => self::MEDICAL,             'cost' => '10¢'],
            ['slug' => 'medical-equipment--epsom-salts',                          'name' => 'Epsom Salts',                                           'section' => self::MEDICAL,             'cost' => '9¢/lb.'],
            ['slug' => 'medical-equipment--indigestion-medicine',                 'name' => 'Indigestion Medicine',                                  'section' => self::MEDICAL,             'cost' => '25¢'],
            ['slug' => 'medical-equipment--laxative-nature-s-remedy',             'name' => 'Laxative, Nature\'s Remedy',                            'section' => self::MEDICAL,             'cost' => '25¢'],
            ['slug' => 'medical-equipment--medical-case',                         'name' => 'Medical Case',                                          'section' => self::MEDICAL,             'cost' => '$10.45'],
            ['slug' => 'medical-equipment--forceps',                              'name' => 'Forceps',                                               'section' => self::MEDICAL,             'cost' => '$3.59'],
            ['slug' => 'medical-equipment--scalpel-set',                          'name' => 'Scalpel Set',                                           'section' => self::MEDICAL,             'cost' => '$1.39'],
            ['slug' => 'medical-equipment--hypodermic-syringes',                  'name' => 'Hypodermic Syringes',                                   'section' => self::MEDICAL,             'cost' => '$12.50'],
            ['slug' => 'medical-equipment--atomizer',                             'name' => 'Atomizer',                                              'section' => self::MEDICAL,             'cost' => '$1.39'],
            ['slug' => 'medical-equipment--gauze-bandages-five-yards',            'name' => 'Gauze Bandages (Five Yards)',                           'section' => self::MEDICAL,             'cost' => '69¢'],
            ['slug' => 'medical-equipment--clinical-thermometer',                 'name' => 'Clinical Thermometer',                                  'section' => self::MEDICAL,             'cost' => '69¢'],
            ['slug' => 'medical-equipment--alcohol-half-gallon',                  'name' => 'Alcohol (Half-gallon)',                                 'section' => self::MEDICAL,             'cost' => '20¢'],
            ['slug' => 'medical-equipment--hard-rubber-syringe',                  'name' => 'Hard Rubber Syringe',                                   'section' => self::MEDICAL,             'cost' => '69¢'],
            ['slug' => 'medical-equipment--maple-crutches',                       'name' => 'Maple Crutches',                                        'section' => self::MEDICAL,             'cost' => '$1.59'],
            ['slug' => 'medical-equipment--adhesive-plaster',                     'name' => 'Adhesive Plaster',                                      'section' => self::MEDICAL,             'cost' => '29¢'],
            ['slug' => 'medical-equipment--metal-arch-supports',                  'name' => 'Metal Arch Supports',                                   'section' => self::MEDICAL,             'cost' => '$1.98'],
            ['slug' => 'medical-equipment--leather-ankle-supports-pair',          'name' => 'Leather Ankle Supports (Pair)',                         'section' => self::MEDICAL,             'cost' => '98¢'],
            ['slug' => 'outdoor-travel-gear--cooking-kit',                        'name' => 'Cooking Kit',                                           'section' => self::OUTDOOR,             'cost' => '$8.98'],
            ['slug' => 'outdoor-travel-gear--camp-stove',                         'name' => 'Camp Stove',                                            'section' => self::OUTDOOR,             'cost' => '$6.10'],
            ['slug' => 'outdoor-travel-gear--vacuum-bottle',                      'name' => 'Vacuum Bottle',                                         'section' => self::OUTDOOR,             'cost' => '89¢'],
            ['slug' => 'outdoor-travel-gear--folding-bathtub',                    'name' => 'Folding Bathtub',                                       'section' => self::OUTDOOR,             'cost' => '$6.79'],
            ['slug' => 'outdoor-travel-gear--waterproof-blanket-58-x-96-inches',  'name' => 'Waterproof Blanket (58 x 96 Inches)',                   'section' => self::OUTDOOR,             'cost' => '$5.06'],
            ['slug' => 'outdoor-travel-gear--folding-camp-bed',                   'name' => 'Folding Camp Bed',                                      'section' => self::OUTDOOR,             'cost' => '$3.65'],
            ['slug' => 'outdoor-travel-gear--carbide-lamp-300-beam',              'name' => 'Carbide Lamp (300\' Beam)',                             'section' => self::OUTDOOR,             'cost' => '$2.59'],
            ['slug' => 'outdoor-travel-gear--can-of-carbide-two-pounds',          'name' => 'Can of Carbide (Two Pounds)',                           'section' => self::OUTDOOR,             'cost' => '25¢'],
            ['slug' => 'outdoor-travel-gear--searchlight',                        'name' => 'Searchlight',                                           'section' => self::OUTDOOR,             'cost' => '$5.95'],
            ['slug' => 'outdoor-travel-gear--gasoline-lantern-built-in-pump',     'name' => 'Gasoline Lantern (Built-in Pump)',                      'section' => self::OUTDOOR,             'cost' => '$6.59'],
            ['slug' => 'outdoor-travel-gear--kerosene-lantern',                   'name' => 'Kerosene Lantern',                                      'section' => self::OUTDOOR,             'cost' => '$1.39'],
            ['slug' => 'outdoor-travel-gear--dark-lantern',                       'name' => 'Dark Lantern',                                          'section' => self::OUTDOOR,             'cost' => '$1.68'],
            ['slug' => 'outdoor-travel-gear--electric-torch',                     'name' => 'Electric Torch',                                        'section' => self::OUTDOOR,             'cost' => '$1.35-$2.25'],
            ['slug' => 'outdoor-travel-gear--batteries',                          'name' => 'Batteries',                                             'section' => self::OUTDOOR,             'cost' => '60¢'],
            ['slug' => 'outdoor-travel-gear--pen-light',                          'name' => 'Pen Light',                                             'section' => self::OUTDOOR,             'cost' => '$1.00'],
            ['slug' => 'outdoor-travel-gear--flare-disposable',                   'name' => 'Flare (Disposable)',                                    'section' => self::OUTDOOR,             'cost' => '27¢'],
            ['slug' => 'outdoor-travel-gear--telescope',                          'name' => 'Telescope',                                             'section' => self::OUTDOOR,             'cost' => '$3.45'],
            ['slug' => 'outdoor-travel-gear--field-glasses-3x-to-6x',             'name' => 'Field Glasses (3x to 6x)',                              'section' => self::OUTDOOR,             'cost' => '$6.00-$23.00'],
            ['slug' => 'outdoor-travel-gear--binoculars',                         'name' => 'Binoculars',                                            'section' => self::OUTDOOR,             'cost' => '$28.50'],
            ['slug' => 'outdoor-travel-gear--jeweled-compass',                    'name' => 'Jeweled Compass',                                       'section' => self::OUTDOOR,             'cost' => '$3.25'],
            ['slug' => 'outdoor-travel-gear--compass-with-lid',                   'name' => 'Compass with Lid',                                      'section' => self::OUTDOOR,             'cost' => '$2.85'],
            ['slug' => 'outdoor-travel-gear--hunting-knife',                      'name' => 'Hunting Knife',                                         'section' => self::OUTDOOR,             'cost' => '$2.35'],
            ['slug' => 'outdoor-travel-gear--heavy-2-blade-pocket-knife',         'name' => 'Heavy 2-Blade Pocket Knife',                            'section' => self::OUTDOOR,             'cost' => '$1.20'],
            ['slug' => 'outdoor-travel-gear--hand-axe',                           'name' => 'Hand Axe',                                              'section' => self::OUTDOOR,             'cost' => '98¢'],
            ['slug' => 'outdoor-travel-gear--small-live-animal-trap',             'name' => 'Small Live Animal Trap',                                'section' => self::OUTDOOR,             'cost' => '$2.48'],
            ['slug' => 'outdoor-travel-gear--coil-spring-animal-trap',            'name' => 'Coil Spring Animal Trap',                               'section' => self::OUTDOOR,             'cost' => '$5.98'],
            ['slug' => 'outdoor-travel-gear--bear-trap',                          'name' => 'Bear Trap',                                             'section' => self::OUTDOOR,             'cost' => '$11.43'],
            ['slug' => 'outdoor-travel-gear--collapsible-fishing-rod-and-tackle-set', 'name' => 'Collapsible Fishing Rod and Tackle Set',                'section' => self::OUTDOOR,             'cost' => '$9.35'],
            ['slug' => 'outdoor-travel-gear--hemp-twine',                         'name' => 'Hemp Twine',                                            'section' => self::OUTDOOR,             'cost' => '27¢'],
            ['slug' => 'outdoor-travel-gear--pedometer',                          'name' => 'Pedometer',                                             'section' => self::OUTDOOR,             'cost' => '$1.70'],
            ['slug' => 'outdoor-travel-gear--heavy-canvas-shoulder-bag',          'name' => 'Heavy Canvas Shoulder Bag',                             'section' => self::OUTDOOR,             'cost' => '$3.45'],
            ['slug' => 'outdoor-travel-gear--fifteen-hour-candles-dozen',         'name' => 'Fifteen Hour Candles (Dozen)',                          'section' => self::OUTDOOR,             'cost' => '62¢'],
            ['slug' => 'outdoor-travel-gear--waterproof-match-case',              'name' => 'Waterproof Match Case',                                 'section' => self::OUTDOOR,             'cost' => '48¢'],
            ['slug' => 'luggage--handle-bag-8-lbs',                               'name' => 'Handle Bag (8 Lbs.)',                                   'section' => self::LUGGAGE,             'cost' => '$7.45'],
            ['slug' => 'luggage--suitcase-15-lbs',                                'name' => 'Suitcase (15 Lbs.)',                                    'section' => self::LUGGAGE,             'cost' => '$9.95'],
            ['slug' => 'luggage--steamer-trunk-55-lbs',                           'name' => 'Steamer Trunk (55 Lbs.)',                               'section' => self::LUGGAGE,             'cost' => '$12.00'],
            ['slug' => 'luggage--wardrobe-trunk-95-lbs',                          'name' => 'Wardrobe Trunk (95 Lbs.)',                              'section' => self::LUGGAGE,             'cost' => '$54.95'],
            ['slug' => 'luggage--wardrobe-trunk-115-lbs',                         'name' => 'Wardrobe Trunk (115 Lbs.)',                             'section' => self::LUGGAGE,             'cost' => '$79.95'],
            ['slug' => 'luggage--luggage-black-patent-leather',                   'name' => 'Luggage, black patent leather',                         'section' => self::LUGGAGE,             'cost' => '$12.50'],
            ['slug' => 'tents-camp--7-x-7-foot-tent',                             'name' => '7 x 7 foot Tent',                                       'section' => self::TENTS,               'cost' => '$11.48'],
            ['slug' => 'tents-camp--12-x-16-foot-tent',                           'name' => '12 x 16 foot Tent',                                     'section' => self::TENTS,               'cost' => '$28.15'],
            ['slug' => 'tents-camp--16-x-24-foot-tent',                           'name' => '16 x 24 foot Tent',                                     'section' => self::TENTS,               'cost' => '$53.48'],
            ['slug' => 'tents-camp--24-x-36-foot-tarpaulin',                      'name' => '24 x 36 foot Tarpaulin',                                'section' => self::TENTS,               'cost' => '$39.35'],
            ['slug' => 'tents-camp--7-x-7-foot-car-tent',                         'name' => '7 x 7 foot Car Tent',                                   'section' => self::TENTS,               'cost' => '$12.80'],
            ['slug' => 'tents-camp--13-5-inch-iron-tent-stakes-dozen',            'name' => '13.5 inch Iron Tent Stakes (Dozen)',                    'section' => self::TENTS,               'cost' => '$1.15'],
            ['slug' => 'tents-camp--auto-bed',                                    'name' => 'Auto Bed',                                              'section' => self::TENTS,               'cost' => '$8.95'],
            ['slug' => 'tents-camp--canteen-1-quart',                             'name' => 'Canteen (1 Quart)',                                     'section' => self::TENTS,               'cost' => '$1.69'],
            ['slug' => 'tents-camp--insulated-tank-5-gallons',                    'name' => 'Insulated Tank (5 Gallons)',                            'section' => self::TENTS,               'cost' => '$3.98'],
            ['slug' => 'tents-camp--water-bag-1-gallon',                          'name' => 'Water Bag (1 Gallon)',                                  'section' => self::TENTS,               'cost' => '80¢'],
            ['slug' => 'tents-camp--water-bag-2-gallon',                          'name' => 'Water Bag (2 Gallon)',                                  'section' => self::TENTS,               'cost' => '$1.04'],
            ['slug' => 'tents-camp--water-bag-5-gallon',                          'name' => 'Water Bag (5 Gallon)',                                  'section' => self::TENTS,               'cost' => '$2.06'],
            ['slug' => 'tools--tool-outfit-20-tools',                             'name' => 'Tool Outfit (20 Tools)',                                'section' => self::TOOLS,               'cost' => '$14.90'],
            ['slug' => 'tools--hand-drill-plus-8-bits',                           'name' => 'Hand Drill (Plus 8 Bits)',                              'section' => self::TOOLS,               'cost' => '$6.15'],
            ['slug' => 'tools--large-steel-pulley',                               'name' => 'Large Steel Pulley',                                    'section' => self::TOOLS,               'cost' => '$1.75'],
            ['slug' => 'tools--padlock',                                          'name' => 'Padlock',                                               'section' => self::TOOLS,               'cost' => '95¢'],
            ['slug' => 'tools--rope-50-feet',                                     'name' => 'Rope (50 Feet)',                                        'section' => self::TOOLS,               'cost' => '$8.60'],
            ['slug' => 'tools--light-chain-per-foot',                             'name' => 'Light Chain (per Foot)',                                'section' => self::TOOLS,               'cost' => '10¢'],
            ['slug' => 'tools--watchmaker-s-tool-kit',                            'name' => 'Watchmaker\'s Tool Kit',                                'section' => self::TOOLS,               'cost' => '$7.74'],
            ['slug' => 'tools--crowbar',                                          'name' => 'Crowbar',                                               'section' => self::TOOLS,               'cost' => '$2.25'],
            ['slug' => 'tools--handsaw',                                          'name' => 'Handsaw',                                               'section' => self::TOOLS,               'cost' => '$1.65'],
            ['slug' => 'tools--gasoline-blowtorch',                               'name' => 'Gasoline Blowtorch',                                    'section' => self::TOOLS,               'cost' => '$4.45'],
            ['slug' => 'tools--electricians-gloves',                              'name' => 'Electricians Gloves',                                   'section' => self::TOOLS,               'cost' => '$1.98'],
            ['slug' => 'tools--lineman-s-tool-belt-safety-strap',                 'name' => 'Lineman\'s Tool Belt & Safety Strap',                   'section' => self::TOOLS,               'cost' => '$3.33'],
            ['slug' => 'tools--lineman-s-climbers',                               'name' => 'Lineman\'s Climbers',                                   'section' => self::TOOLS,               'cost' => '$2.52'],
            ['slug' => 'tools--jewelers-48-piece-tool-set',                       'name' => 'Jewelers 48-Piece Tool Set',                            'section' => self::TOOLS,               'cost' => '$15.98'],
            ['slug' => 'tools--rotary-tool-grinder',                              'name' => 'Rotary Tool Grinder',                                   'section' => self::TOOLS,               'cost' => '$6.90'],
            ['slug' => 'tools--shovel',                                           'name' => 'Shovel',                                                'section' => self::TOOLS,               'cost' => '95¢'],
            ['slug' => 'tools--home-tool-set-in-box',                             'name' => 'Home Tool Set in Box',                                  'section' => self::TOOLS,               'cost' => '$14.90'],
            ['slug' => 'investigator-tools--handcuffs',                           'name' => 'Handcuffs',                                             'section' => self::INVESTIGATOR,        'cost' => '$3.35'],
            ['slug' => 'investigator-tools--extra-handcuff-key',                  'name' => 'Extra Handcuff Key',                                    'section' => self::INVESTIGATOR,        'cost' => '28¢'],
            ['slug' => 'investigator-tools--police-whistle',                      'name' => 'Police Whistle',                                        'section' => self::INVESTIGATOR,        'cost' => '30¢'],
            ['slug' => 'investigator-tools--dictaphone',                          'name' => 'Dictaphone',                                            'section' => self::INVESTIGATOR,        'cost' => '$39.95'],
            ['slug' => 'investigator-tools--wire-recorder',                       'name' => 'Wire Recorder',                                         'section' => self::INVESTIGATOR,        'cost' => '$129.95'],
            ['slug' => 'investigator-tools--wristwatch',                          'name' => 'Wristwatch',                                            'section' => self::INVESTIGATOR,        'cost' => '$5.95'],
            ['slug' => 'investigator-tools--gold-pocket-watch',                   'name' => 'Gold Pocket Watch',                                     'section' => self::INVESTIGATOR,        'cost' => '$35.10'],
            ['slug' => 'investigator-tools--self-filling-fountain-pen',           'name' => 'Self-filling Fountain Pen',                             'section' => self::INVESTIGATOR,        'cost' => '$1.80'],
            ['slug' => 'investigator-tools--mechanical-pencil',                   'name' => 'Mechanical Pencil',                                     'section' => self::INVESTIGATOR,        'cost' => '85¢'],
            ['slug' => 'investigator-tools--writing-tablet',                      'name' => 'Writing Tablet',                                        'section' => self::INVESTIGATOR,        'cost' => '20¢'],
            ['slug' => 'investigator-tools--straightjacket',                      'name' => 'Straightjacket',                                        'section' => self::INVESTIGATOR,        'cost' => '$9.50'],
            ['slug' => 'investigator-tools--sketch-pad',                          'name' => 'Sketch Pad',                                            'section' => self::INVESTIGATOR,        'cost' => '25¢'],
            ['slug' => 'investigator-tools--complete-diving-suit',                'name' => 'Complete Diving Suit',                                  'section' => self::INVESTIGATOR,        'cost' => '$1,200.00'],
            ['slug' => 'investigator-tools--remington-typewriter',                'name' => 'Remington Typewriter',                                  'section' => self::INVESTIGATOR,        'cost' => '$40.00'],
            ['slug' => 'investigator-tools--harris-typewriter',                   'name' => 'Harris Typewriter',                                     'section' => self::INVESTIGATOR,        'cost' => '$66.75'],
            ['slug' => 'investigator-tools--pocket-microscope',                   'name' => 'Pocket Microscope',                                     'section' => self::INVESTIGATOR,        'cost' => '58¢'],
            ['slug' => 'investigator-tools--110x-desk-microscope',                'name' => '110x Desk Microscope',                                  'section' => self::INVESTIGATOR,        'cost' => '$17.50'],
            ['slug' => 'investigator-tools--umbrella',                            'name' => 'Umbrella',                                              'section' => self::INVESTIGATOR,        'cost' => '$1.79'],
            ['slug' => 'investigator-tools--turkish-water-pipe',                  'name' => 'Turkish Water Pipe',                                    'section' => self::INVESTIGATOR,        'cost' => '99¢'],
            ['slug' => 'investigator-tools--cigarettes-per-pack',                 'name' => 'Cigarettes (per Pack)',                                 'section' => self::INVESTIGATOR,        'cost' => '10¢'],
            ['slug' => 'investigator-tools--box-of-cigars',                       'name' => 'Box of Cigars',                                         'section' => self::INVESTIGATOR,        'cost' => '$2.29'],
            ['slug' => 'investigator-tools--unabridged-dictionary',               'name' => 'Unabridged Dictionary',                                 'section' => self::INVESTIGATOR,        'cost' => '$6.75'],
            ['slug' => 'investigator-tools--10-volume-encyclopedia',              'name' => '10-volume Encyclopedia',                                'section' => self::INVESTIGATOR,        'cost' => '$49.00'],
            ['slug' => 'investigator-tools--wet-sponge-respirator',               'name' => 'Wet Sponge Respirator',                                 'section' => self::INVESTIGATOR,        'cost' => '$1.95'],
            ['slug' => 'investigator-tools--3-lens-pocket-magnifying-glass-7x-to-30x', 'name' => '3-Lens Pocket Magnifying Glass (7x to 30x)',            'section' => self::INVESTIGATOR,        'cost' => '$1.68'],
            ['slug' => 'investigator-tools--bible',                               'name' => 'Bible',                                                 'section' => self::INVESTIGATOR,        'cost' => '$3.98'],
            ['slug' => 'investigator-tools--briefcase',                           'name' => 'Briefcase',                                             'section' => self::INVESTIGATOR,        'cost' => '$1.48'],
            ['slug' => 'investigator-tools--folding-writing-desk',                'name' => 'Folding Writing Desk',                                  'section' => self::INVESTIGATOR,        'cost' => '$16.65'],
            ['slug' => 'investigator-tools--chemical-fire-extinguisher',          'name' => 'Chemical Fire Extinguisher',                            'section' => self::INVESTIGATOR,        'cost' => '$13.85'],
            ['slug' => 'investigator-tools--watchmaker-s-eye-glass',              'name' => 'Watchmaker\'s Eye Glass',                               'section' => self::INVESTIGATOR,        'cost' => '45¢'],
            ['slug' => 'communications--postcard',                                'name' => 'Postcard',                                              'section' => self::COMMUNICATIONS,      'cost' => '5¢-20¢'],
            ['slug' => 'communications--telegraph-outfit',                        'name' => 'Telegraph Outfit',                                      'section' => self::COMMUNICATIONS,      'cost' => '$4.25'],
            ['slug' => 'communications--newspaper',                               'name' => 'Newspaper',                                             'section' => self::COMMUNICATIONS,      'cost' => '5¢'],
            ['slug' => 'entertainment--4-string-jazz-banjo',                      'name' => '4-string Jazz Banjo',                                   'section' => self::ENTERTAINMENT,       'cost' => '$7.45'],
            ['slug' => 'entertainment--brass-saxophone',                          'name' => 'Brass Saxophone',                                       'section' => self::ENTERTAINMENT,       'cost' => '$69.75'],
            ['slug' => 'entertainment--phonograph-records',                       'name' => 'Phonograph Records',                                    'section' => self::ENTERTAINMENT,       'cost' => '75¢'],
            ['slug' => 'entertainment--box-brownie-camera',                       'name' => 'Box Brownie Camera',                                    'section' => self::ENTERTAINMENT,       'cost' => '$2.29-$4.49'],
            ['slug' => 'entertainment--film-24-exposures',                        'name' => 'Film, 24 Exposures',                                    'section' => self::ENTERTAINMENT,       'cost' => '38¢'],
            ['slug' => 'entertainment--film-developing-kit',                      'name' => 'Film Developing Kit',                                   'section' => self::ENTERTAINMENT,       'cost' => '$4.95'],
            ['slug' => 'entertainment--kodak-folding-no-1-camera',                'name' => 'Kodak Folding No.1 Camera',                             'section' => self::ENTERTAINMENT,       'cost' => '$4.25-$28.00'],
            ['slug' => 'entertainment--eastman-commercial-camera',                'name' => 'Eastman Commercial Camera',                             'section' => self::ENTERTAINMENT,       'cost' => '$140.00'],
            ['slug' => 'entertainment--16mm-movie-camera-projector',              'name' => '16mm Movie Camera & Projector',                         'section' => self::ENTERTAINMENT,       'cost' => '$335.00'],
            ['slug' => 'entertainment--portable-radio-receiver',                  'name' => 'Portable Radio Receiver',                               'section' => self::ENTERTAINMENT,       'cost' => '$65.00'],
            ['slug' => 'entertainment--accordion',                                'name' => 'Accordion',                                             'section' => self::ENTERTAINMENT,       'cost' => '$8.95'],
            ['slug' => 'entertainment--ukulele-kit',                              'name' => 'Ukulele (Kit)',                                         'section' => self::ENTERTAINMENT,       'cost' => '$2.75'],
            ['slug' => 'entertainment--guitar-kit',                               'name' => 'Guitar (Kit)',                                          'section' => self::ENTERTAINMENT,       'cost' => '$9.95'],
            ['slug' => 'entertainment--violin-kit',                               'name' => 'Violin (Kit)',                                          'section' => self::ENTERTAINMENT,       'cost' => '$14.95'],
            ['slug' => 'entertainment--army-bugle',                               'name' => 'Army Bugle',                                            'section' => self::ENTERTAINMENT,       'cost' => '$3.45'],
            ['slug' => 'sports-games--150-clay-marbles',                          'name' => '150 Clay Marbles',                                      'section' => self::SPORTS,              'cost' => '15¢'],
            ['slug' => 'sports-games--25-glass-marbles',                          'name' => '25 Glass Marbles',                                      'section' => self::SPORTS,              'cost' => '33¢'],
            ['slug' => 'sports-games--baseball-mitt',                             'name' => 'Baseball Mitt',                                         'section' => self::SPORTS,              'cost' => '$5.45'],
            ['slug' => 'sports-games--baseball-bat',                              'name' => 'Baseball Bat',                                          'section' => self::SPORTS,              'cost' => '$1.30'],
            ['slug' => 'sports-games--baseball-catcher-s-pads-mask-set',          'name' => 'Baseball Catcher\'s Pads & Mask Set',                   'section' => self::SPORTS,              'cost' => '$14.10'],
            ['slug' => 'sports-games--baseball',                                  'name' => 'Baseball',                                              'section' => self::SPORTS,              'cost' => '55¢'],
            ['slug' => 'sports-games--basketball',                                'name' => 'Basketball',                                            'section' => self::SPORTS,              'cost' => '$6.75'],
            ['slug' => 'sports-games--rugby-football',                            'name' => 'Rugby Football',                                        'section' => self::SPORTS,              'cost' => '$4.15'],
            ['slug' => 'sports-games--roller-skates',                             'name' => 'Roller Skates',                                         'section' => self::SPORTS,              'cost' => '$1.65'],
            ['slug' => 'sports-games--tennis-racket-balls-3-pack',                'name' => 'Tennis Racket & Balls (3-pack)',                        'section' => self::SPORTS,              'cost' => '$4.82'],
            ['slug' => 'sports-games--beginner-golf-set-w-bag',                   'name' => 'Beginner Golf Set w/ Bag',                              'section' => self::SPORTS,              'cost' => '$9.25'],
            ['slug' => 'sports-games--pro-steel-golf-club',                       'name' => 'Pro Steel Golf Club',                                   'section' => self::SPORTS,              'cost' => '$6.15'],
            ['slug' => 'sports-games--golf-bag',                                  'name' => 'Golf Bag',                                              'section' => self::SPORTS,              'cost' => '$5.95'],
            ['slug' => 'sports-games--boxing-gloves',                             'name' => 'Boxing Gloves',                                         'section' => self::SPORTS,              'cost' => '$3.75'],
            ['slug' => 'sports-games--dumbbells-five-pound-pair',                 'name' => 'Dumbbells, five pound (Pair)',                          'section' => self::SPORTS,              'cost' => '$1.68'],
            ['slug' => 'sports-games--bamboo-vaulting-pole-12-foot',              'name' => 'Bamboo Vaulting Pole, 12 foot',                         'section' => self::SPORTS,              'cost' => '$7.40'],
            ['slug' => 'sports-games--playing-cards',                             'name' => 'Playing Cards',                                         'section' => self::SPORTS,              'cost' => '59¢'],
            ['slug' => 'sports-games--ouija-board',                               'name' => 'Ouija Board',                                           'section' => self::SPORTS,              'cost' => '98¢'],
            ['slug' => 'sports-games--dominoes',                                  'name' => 'Dominoes',                                              'section' => self::SPORTS,              'cost' => '59¢'],
            ['slug' => 'sports-games--chess-set',                                 'name' => 'Chess Set',                                             'section' => self::SPORTS,              'cost' => '$1.39'],
            ['slug' => 'sports-games--croquet-set',                               'name' => 'Croquet Set',                                           'section' => self::SPORTS,              'cost' => '$2.30'],
            ['slug' => 'sports-games--billiard-cue',                              'name' => 'Billiard Cue',                                          'section' => self::SPORTS,              'cost' => '$1.99'],
            ['slug' => 'sports-games--mahjong-set',                               'name' => 'Mahjong Set',                                           'section' => self::SPORTS,              'cost' => '$1.80'],
            ['slug' => 'ammunition--22-long-rifle-box-of-100',                    'name' => '.22 Long Rifle (Box of 100)',                           'section' => self::AMMUNITION,          'cost' => '54¢'],
            ['slug' => 'ammunition--22-hollow-point-box-of-100',                  'name' => '.22 Hollow Point (Box of 100)',                         'section' => self::AMMUNITION,          'cost' => '53¢'],
            ['slug' => 'ammunition--25-rimfire-box-of-100',                       'name' => '.25 Rimfire (Box of 100)',                              'section' => self::AMMUNITION,          'cost' => '$1.34'],
            ['slug' => 'ammunition--30-06-gov-t-box-of-100',                      'name' => '.30-06 Gov\'t (Box of 100)',                            'section' => self::AMMUNITION,          'cost' => '$7.63'],
            ['slug' => 'ammunition--32-special-box-of-100',                       'name' => '.32 Special (Box of 100)',                              'section' => self::AMMUNITION,          'cost' => '$5.95'],
            ['slug' => 'ammunition--32-20-repeater-box-of-100',                   'name' => '.32-20 Repeater (Box of 100)',                          'section' => self::AMMUNITION,          'cost' => '$2.97'],
            ['slug' => 'ammunition--38-short-round-box-of-100',                   'name' => '.38 Short Round (Box of 100)',                          'section' => self::AMMUNITION,          'cost' => '$1.75'],
            ['slug' => 'ammunition--38-55-repeater-box-of-100',                   'name' => '.38-55 Repeater (Box of 100)',                          'section' => self::AMMUNITION,          'cost' => '$6.60'],
            ['slug' => 'ammunition--44-hi-power-box-of-100',                      'name' => '.44 Hi-Power (Box of 100)',                             'section' => self::AMMUNITION,          'cost' => '$4.49'],
            ['slug' => 'ammunition--45-automatic-box-of-100',                     'name' => '.45 Automatic (Box of 100)',                            'section' => self::AMMUNITION,          'cost' => '$4.43'],
            ['slug' => 'ammunition--10-gauge-shell-box-of-25',                    'name' => '10-Gauge Shell (Box of 25)',                            'section' => self::AMMUNITION,          'cost' => '$1.00'],
            ['slug' => 'ammunition--10-gauge-shell-box-of-100',                   'name' => '10-Gauge Shell (Box of 100)',                           'section' => self::AMMUNITION,          'cost' => '$3.91'],
            ['slug' => 'ammunition--12-gauge-shell-box-of-25',                    'name' => '12-Gauge Shell (Box of 25)',                            'section' => self::AMMUNITION,          'cost' => '93¢'],
            ['slug' => 'ammunition--12-gauge-shell-box-of-100',                   'name' => '12-Gauge Shell (Box of 100)',                           'section' => self::AMMUNITION,          'cost' => '$3.63'],
            ['slug' => 'ammunition--16-gauge-shell-box-of-25',                    'name' => '16-Gauge Shell (Box of 25)',                            'section' => self::AMMUNITION,          'cost' => '86¢'],
            ['slug' => 'ammunition--16-gauge-shell-box-of-100',                   'name' => '16-Gauge Shell (Box of 100)',                           'section' => self::AMMUNITION,          'cost' => '$3.34'],
            ['slug' => 'ammunition--20-gauge-shell-box-of-25',                    'name' => '20-Gauge Shell (Box of 25)',                            'section' => self::AMMUNITION,          'cost' => '85¢'],
            ['slug' => 'ammunition--20-gauge-shell-box-of-100',                   'name' => '20-Gauge Shell (Box of 100)',                           'section' => self::AMMUNITION,          'cost' => '$3.30'],
            ['slug' => 'ammunition--12-gauge-single-barrel-shotgun-kit-30',       'name' => '12-Gauge Single-barrel Shotgun Kit* (30")',             'section' => self::AMMUNITION,          'cost' => '$9.20'],
            ['slug' => 'ammunition--12-gauge-double-barrel-shotgun-kit-30',       'name' => '12-Gauge Double-barrel Shotgun Kit* (30")',             'section' => self::AMMUNITION,          'cost' => '$21.35'],
            ['slug' => 'ammunition--extra-magazine-for-pistol',                   'name' => 'Extra Magazine for Pistol',                             'section' => self::AMMUNITION,          'cost' => '$1.90'],        ];
    }
}
