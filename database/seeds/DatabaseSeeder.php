<?php //b4f2e6d787e3632e35b6465fb958eef5

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Code;
use App\Models\Menu;
use App\Models\Product;
use App\Models\License;
use App\Models\Folio;
use App\Models\LicenseMenu;
use App\Models\CompanyLicense;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
/***********************************************************************/
/**                                                                    */
/**               Semillas de regiones y comunas                       */
/**                                                                    */
/***********************************************************************/ 
        $now = \Carbon\Carbon::now();
        $regions = [
            [1,'Arica y Parinacota','XV'],
            [2,'Tarapacá','I'],
            [3,'Antofagasta','II'],
            [4,'Atacama','III'],
            [5,'Coquimbo','IV'],
            [6,'Valparaiso','V'],
            [7,'Metropolitana de Santiago','RM'],
            [8,'Libertador General Bernardo O\'Higgins','VI'],
            [9,'Maule','VII'],
            [10,'Biobío','VIII'],
            [11,'La Araucanía','IX'],
            [12,'Los Ríos','XIV'],
            [13,'Los Lagos','X'],
            [14,'Aisén del General Carlos Ibáñez del Campo','XI'],
            [15,'Magallanes y de la Antártica Chilena','XII']
        ];
        $regions = array_map(function($region) use ($now) {
           return [
               'id'         => $region[0],
               'name'       => $region[1],
               'ordinal'    => $region[2],
               'updated_at' => $now,
               'created_at' => $now,
           ];
        }, $regions);
        \DB::table('regions')->insert($regions);
        $communes = [
            [1,'Arica',1],
            [2,'Camarones',1],
            [3,'General Lagos',1],
            [4,'Putre',1],
            [5,'Alto Hospicio',2],
            [6,'Iquique',2],
            [7,'Camiña',2],
            [8,'Colchane',2],
            [9,'Huara',2],
            [10,'Pica',2],
            [11,'Pozo Almonte',2],
            [12,'Antofagasta',3],
            [13,'Mejillones',3],
            [14,'Sierra Gorda',3],
            [15,'Taltal',3],
            [16,'Calama',3],
            [17,'Ollague',3],
            [18,'San Pedro de Atacama',3],
            [19,'María Elena',3],
            [20,'Tocopilla',3],
            [21,'Chañaral',4],
            [22,'Diego de Almagro',4],
            [23,'Caldera',4],
            [24,'Copiapó',4],
            [25,'Tierra Amarilla',4],
            [26,'Alto del Carmen',4],
            [27,'Freirina',4],
            [28,'Huasco',4],
            [29,'Vallenar',4],
            [30,'Canela',5],
            [31,'Illapel',5],
            [32,'Los Vilos',5],
            [33,'Salamanca',5],
            [34,'Andacollo',5],
            [35,'Coquimbo',5],
            [36,'La Higuera',5],
            [37,'La Serena',5],
            [38,'Paihuaco',5],
            [39,'Vicuña',5],
            [40,'Combarbalá',5],
            [41,'Monte Patria',5],
            [42,'Ovalle',5],
            [43,'Punitaqui',5],
            [44,'Río Hurtado',5],
            [45,'Isla de Pascua',6],
            [46,'Calle Larga',6],
            [47,'Los Andes',6],
            [48,'Rinconada',6],
            [49,'San Esteban',6],
            [50,'La Ligua',6],
            [51,'Papudo',6],
            [52,'Petorca',6],
            [53,'Zapallar',6],
            [54,'Hijuelas',6],
            [55,'La Calera',6],
            [56,'La Cruz',6],
            [57,'Limache',6],
            [58,'Nogales',6],
            [59,'Olmué',6],
            [60,'Quillota',6],
            [61,'Algarrobo',6],
            [62,'Cartagena',6],
            [63,'El Quisco',6],
            [64,'El Tabo',6],
            [65,'San Antonio',6],
            [66,'Santo Domingo',6],
            [67,'Catemu',6],
            [68,'Llaillay',6],
            [69,'Panquehue',6],
            [70,'Putaendo',6],
            [71,'San Felipe',6],
            [72,'Santa María',6],
            [73,'Casablanca',6],
            [74,'Concón',6],
            [75,'Juan Fernández',6],
            [76,'Puchuncaví',6],
            [77,'Quilpué',6],
            [78,'Quintero',6],
            [79,'Valparaíso',6],
            [80,'Villa Alemana',6],
            [81,'Viña del Mar',6],
            [82,'Colina',7],
            [83,'Lampa',7],
            [84,'Tiltil',7],
            [85,'Pirque',7],
            [86,'Puente Alto',7],
            [87,'San José de Maipo',7],
            [88,'Buin',7],
            [89,'Calera de Tango',7],
            [90,'Paine',7],
            [91,'San Bernardo',7],
            [92,'Alhué',7],
            [93,'Curacaví',7],
            [94,'María Pinto',7],
            [95,'Melipilla',7],
            [96,'San Pedro',7],
            [97,'Cerrillos',7],
            [98,'Cerro Navia',7],
            [99,'Conchalí',7],
            [100,'El Bosque',7],
            [101,'Estación Central',7],
            [102,'Huechuraba',7],
            [103,'Independencia',7],
            [104,'La Cisterna',7],
            [105,'La Granja',7],
            [106,'La Florida',7],
            [107,'La Pintana',7],
            [108,'La Reina',7],
            [109,'Las Condes',7],
            [110,'Lo Barnechea',7],
            [111,'Lo Espejo',7],
            [112,'Lo Prado',7],
            [113,'Macul',7],
            [114,'Maipú',7],
            [115,'Ñuñoa',7],
            [116,'Pedro Aguirre Cerda',7],
            [117,'Peñalolén',7],
            [118,'Providencia',7],
            [119,'Pudahuel',7],
            [120,'Quilicura',7],
            [121,'Quinta Normal',7],
            [122,'Recoleta',7],
            [123,'Renca',7],
            [124,'San Miguel',7],
            [125,'San Joaquín',7],
            [126,'San Ramón',7],
            [127,'Santiago',7],
            [128,'Vitacura',7],
            [129,'El Monte',7],
            [130,'Isla de Maipo',7],
            [131,'Padre Hurtado',7],
            [132,'Peñaflor',7],
            [133,'Talagante',7],
            [134,'Codegua',8],
            [135,'Coínco',8],
            [136,'Coltauco',8],
            [137,'Doñihue',8],
            [138,'Graneros',8],
            [139,'Las Cabras',8],
            [140,'Machalí',8],
            [141,'Malloa',8],
            [142,'Mostazal',8],
            [143,'Olivar',8],
            [144,'Peumo',8],
            [145,'Pichidegua',8],
            [146,'Quinta de Tilcoco',8],
            [147,'Rancagua',8],
            [148,'Rengo',8],
            [149,'Requínoa',8],
            [150,'San Vicente de Tagua Tagua',8],
            [151,'La Estrella',8],
            [152,'Litueche',8],
            [153,'Marchihue',8],
            [154,'Navidad',8],
            [155,'Peredones',8],
            [156,'Pichilemu',8],
            [157,'Chépica',8],
            [158,'Chimbarongo',8],
            [159,'Lolol',8],
            [160,'Nancagua',8],
            [161,'Palmilla',8],
            [162,'Peralillo',8],
            [163,'Placilla',8],
            [164,'Pumanque',8],
            [165,'San Fernando',8],
            [166,'Santa Cruz',8],
            [167,'Cauquenes',9],
            [168,'Chanco',9],
            [169,'Pelluhue',9],
            [170,'Curicó',9],
            [171,'Hualañé',9],
            [172,'Licantén',9],
            [173,'Molina',9],
            [174,'Rauco',9],
            [175,'Romeral',9],
            [176,'Sagrada Familia',9],
            [177,'Teno',9],
            [178,'Vichuquén',9],
            [179,'Colbún',9],
            [180,'Linares',9],
            [181,'Longaví',9],
            [182,'Parral',9],
            [183,'Retiro',9],
            [184,'San Javier',9],
            [185,'Villa Alegre',9],
            [186,'Yerbas Buenas',9],
            [187,'Constitución',9],
            [188,'Curepto',9],
            [189,'Empedrado',9],
            [190,'Maule',9],
            [191,'Pelarco',9],
            [192,'Pencahue',9],
            [193,'Río Claro',9],
            [194,'San Clemente',9],
            [195,'San Rafael',9],
            [196,'Talca',9],
            [197,'Arauco',10],
            [198,'Cañete',10],
            [199,'Contulmo',10],
            [200,'Curanilahue',10],
            [201,'Lebu',10],
            [202,'Los Álamos',10],
            [203,'Tirúa',10],
            [204,'Alto Biobío',10],
            [205,'Antuco',10],
            [206,'Cabrero',10],
            [207,'Laja',10],
            [208,'Los Ángeles',10],
            [209,'Mulchén',10],
            [210,'Nacimiento',10],
            [211,'Negrete',10],
            [212,'Quilaco',10],
            [213,'Quilleco',10],
            [214,'San Rosendo',10],
            [215,'Santa Bárbara',10],
            [216,'Tucapel',10],
            [217,'Yumbel',10],
            [218,'Chiguayante',10],
            [219,'Concepción',10],
            [220,'Coronel',10],
            [221,'Florida',10],
            [222,'Hualpén',10],
            [223,'Hualqui',10],
            [224,'Lota',10],
            [225,'Penco',10],
            [226,'San Pedro de La Paz',10],
            [227,'Santa Juana',10],
            [228,'Talcahuano',10],
            [229,'Tomé',10],
            [230,'Bulnes',10],
            [231,'Chillán',10],
            [232,'Chillán Viejo',10],
            [233,'Cobquecura',10],
            [234,'Coelemu',10],
            [235,'Coihueco',10],
            [236,'El Carmen',10],
            [237,'Ninhue',10],
            [238,'Ñiquen',10],
            [239,'Pemuco',10],
            [240,'Pinto',10],
            [241,'Portezuelo',10],
            [242,'Quillón',10],
            [243,'Quirihue',10],
            [244,'Ránquil',10],
            [245,'San Carlos',10],
            [246,'San Fabián',10],
            [247,'San Ignacio',10],
            [248,'San Nicolás',10],
            [249,'Treguaco',10],
            [250,'Yungay',10],
            [251,'Carahue',11],
            [252,'Cholchol',11],
            [253,'Cunco',11],
            [254,'Curarrehue',11],
            [255,'Freire',11],
            [256,'Galvarino',11],
            [257,'Gorbea',11],
            [258,'Lautaro',11],
            [259,'Loncoche',11],
            [260,'Melipeuco',11],
            [261,'Nueva Imperial',11],
            [262,'Padre Las Casas',11],
            [263,'Perquenco',11],
            [264,'Pitrufquén',11],
            [265,'Pucón',11],
            [266,'Saavedra',11],
            [267,'Temuco',11],
            [268,'Teodoro Schmidt',11],
            [269,'Toltén',11],
            [270,'Vilcún',11],
            [271,'Villarrica',11],
            [272,'Angol',11],
            [273,'Collipulli',11],
            [274,'Curacautín',11],
            [275,'Ercilla',11],
            [276,'Lonquimay',11],
            [277,'Los Sauces',11],
            [278,'Lumaco',11],
            [279,'Purén',11],
            [280,'Renaico',11],
            [281,'Traiguén',11],
            [282,'Victoria',11],
            [283,'Corral',12],
            [284,'Lanco',12],
            [285,'Los Lagos',12],
            [286,'Máfil',12],
            [287,'Mariquina',12],
            [288,'Paillaco',12],
            [289,'Panguipulli',12],
            [290,'Valdivia',12],
            [291,'Futrono',12],
            [292,'La Unión',12],
            [293,'Lago Ranco',12],
            [294,'Río Bueno',12],
            [295,'Ancud',13],
            [296,'Castro',13],
            [297,'Chonchi',13],
            [298,'Curaco de Vélez',13],
            [299,'Dalcahue',13],
            [300,'Puqueldón',13],
            [301,'Queilén',13],
            [302,'Quemchi',13],
            [303,'Quellón',13],
            [304,'Quinchao',13],
            [305,'Calbuco',13],
            [306,'Cochamó',13],
            [307,'Fresia',13],
            [308,'Frutillar',13],
            [309,'Llanquihue',13],
            [310,'Los Muermos',13],
            [311,'Maullín',13],
            [312,'Puerto Montt',13],
            [313,'Puerto Varas',13],
            [314,'Osorno',13],
            [315,'Puero Octay',13],
            [316,'Purranque',13],
            [317,'Puyehue',13],
            [318,'Río Negro',13],
            [319,'San Juan de la Costa',13],
            [320,'San Pablo',13],
            [321,'Chaitén',13],
            [322,'Futaleufú',13],
            [323,'Hualaihué',13],
            [324,'Palena',13],
            [325,'Aisén',14],
            [326,'Cisnes',14],
            [327,'Guaitecas',14],
            [328,'Cochrane',14],
            [329,'O\'higgins',14],
            [330,'Tortel',14],
            [331,'Coihaique',14],
            [332,'Lago Verde',14],
            [333,'Chile Chico',14],
            [334,'Río Ibáñez',14],
            [335,'Antártica',15],
            [336,'Cabo de Hornos',15],
            [337,'Laguna Blanca',15],
            [338,'Punta Arenas',15],
            [339,'Río Verde',15],
            [340,'San Gregorio',15],
            [341,'Porvenir',15],
            [342,'Primavera',15],
            [343,'Timaukel',15],
            [344,'Natales',15],
            [345,'Torres del Paine',15],
            [346,'Cabildo',6]
        ];
        $communes = array_map(function($commune) use ($now) {
            return [
                'id' => $commune[0],
                'name' => $commune[1],
                'region_id' => $commune[2],
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }, $communes);
        \DB::table('communes')->insert($communes); 
/***********************************************************************/
/**                                                                    */
/**               Semillas de licencias                                */
/**                                                                    */
/***********************************************************************/ 
        License::insert([           
            'type' => 'SPECIAL',
            'code'             => 'L_AUTO_ADD_PRODUCT_TO_COMPANY',
            'label'            => 'Autocargar producto a una compañía',
            'Description'      => 'Al momento de crear un nuevo producto, este automáticamente es agregado al maestro de la compañía'            
        ]);

        License::insert([
            'type' => 'SPECIAL',           
            'code'             => 'L_GEOLOCATION',
            'label'            => 'Módulos Geolocalización de Entidades',
            'Description'      => 'Las empresas prodrán definir coordenadas de entidades y obtener así su geolocalización en GoogleMaps, además de crear multiples direcciones para una entidad.'            
        ]);
      
        /**Licencias para reportes */
            License::insert([  
                'type'        => 'REPORT',         
                'code'        => 'L_R_CRITIKL_STOCK',
                'label'       => 'Licencia para reporte de stock crítico',
                'Description' => 'La empresa puede ver reporte de stock crítico de productos.'            
            ]);
        /**Licencias que se aplican sobre los módulos. */

        License::insert([  
            'type' => 'MODULE',         
            'code'             => 'L_M_MY_PROFILE',
            'label'            => 'Licencia módulo de ver mi perfil',
            'Description'      => 'La empresa puede gestionar la información de perfiles.'            
        ]);
        License::insert([  
            'type' => 'MODULE',         
            'code'             => 'L_M_GEO_DASH',
            'label'            => 'Licencia dashboard geolocalizacion',
            'Description'      => 'La empresa ve inicialmente un mapa con indicadores.'            
        ]);
        License::insert([     
            'type' => 'MODULE',      
            'code'             => 'L_M_DASH_STADISTIC',
            'label'            => 'Licencia módulo de perfiles',
            'Description'      => 'La empresa puede gestionar la información de perfiles.'            
        ]);
        License::insert([ 
            'type' => 'MODULE',           
            'code'             => 'L_M_PROFILES',
            'label'            => 'Licencia módulo de perfiles',
            'Description'      => 'La empresa puede gestionar la información de perfiles.'            
        ]);

        License::insert([     
            'type' => 'MODULE',      
            'code'             => 'L_M_BRANDS',
            'label'            => 'Licencia módulo de marcas',
            'Description'      => 'La empresa puede gestionar la información de marcas.'            
        ]);

        License::insert([   
            'type' => 'MODULE',        
            'code'             => 'L_M_USER_COMPANY',
            'label'            => 'Licencia módulo de usuarios monoempresa',
            'Description'      => 'La empresa puede gestionar usuarios en formato mono empresa.'            
        ]);

        License::insert([        
            'type' => 'MODULE',   
            'code'             => 'L_M_USER',
            'label'            => 'Licencia módulo de usuarios',
            'Description'      => 'La empresa puede gestionar usuarios.'            
        ]);

        License::insert([     
            'type' => 'MODULE',      
            'code'             => 'L_M_COMPANY',
            'label'            => 'Licencia módulo de empresas',
            'Description'      => 'La empresa puede gestionar empresas.'            
        ]);

        License::insert([     
            'type' => 'MODULE',      
            'code'             => 'L_M_PRODUCT',
            'label'            => 'Licencia módulo de productos',
            'Description'      => 'La empresa puede gestionar productos.'            
        ]);

        License::insert([           
            'type' => 'MODULE',
            'code'             => 'L_M_CUSTOMERS',
            'label'            => 'Licencia módulo de clientes',
            'Description'      => 'La empresa puede gestionar clientes.'            
        ]);

        License::insert([      
            'type' => 'MODULE',     
            'code'             => 'L_M_CATEGORIES',
            'label'            => 'Licencia módulo de categorias',
            'Description'      => 'La empresa puede gestionar categorías.'            
        ]);

        License::insert([         
            'type' => 'MODULE',  
            'code'             => 'L_M_PROVIDERS',
            'label'            => 'Licencia para módulo de licencias',
            'Description'      => 'La empresa puede gestionar proveerdores.'            
        ]);

        License::insert([       
            'type' => 'MODULE',    
            'code'             => 'L_M_MENUS',
            'label'            => 'Licencia módulo de menus',
            'Description'      => 'La empresa puede gestionar manus.'            
        ]);

        License::insert([    
            'type' => 'MODULE',       
            'code'             => 'L_M_LICENSES',
            'label'            => 'Licencia módulo de licencias',
            'Description'      => 'La empresa puede gestionar licencias.'            
        ]);

        License::insert([     
            'type' => 'MODULE',      
            'code'             => 'L_M_PRODUCT_COMPANY',
            'label'            => 'Licencia módulo de productos de la empresa',
            'Description'      => 'La empresa puede gestionar ciertos datos de productos asociados a ella (formato multiempresa).'            
        ]);

        License::insert([       
            'type' => 'MODULE',    
            'code'             => 'L_M_RECEPTIONS',
            'label'            => 'Licencia módulo de gestión de recepciones',
            'Description'      => 'La empresa puede realizar eventos de recepción de productos'
        ]);

        License::insert([  
            'type' => 'MODULE',         
            'code'             => 'L_M_INVENTORIES',
            'label'            => 'Licencia módulo de gestión de inventarios',
            'Description'      => 'La empresa puede realizar eventos de inventarios de productos'
        ]);

        License::insert([  
            'type' => 'MODULE',         
            'code'             => 'L_M_PRODUCT_CONFIG',
            'label'            => 'Licencia módulo productos mono empresa',
            'Description'      => 'La empresa puede gestionar todos sus productos (formano monoempresa)'
        ]);

        License::insert([  
            'type' => 'MODULE',         
            'code'             => 'L_M_SALES_TICKET',
            'label'            => 'Licencia para la emisión de ticket de venta',
            'Description'      => 'La empresa puede generar tickets de venta.'
        ]);

        License::insert([   
             'type' => 'MODULE',        
            'code'             => 'L_M_FOLIOS_CONFIG',
            'label'            => 'Licencia módulo de folios',
            'Description'      => 'La empresa puede configurar los folios de sus documentos.'
        ]);

        License::insert([   
            'type'            => 'MODULE',        
           'code'             => 'L_M_SALES_LIST',
           'label'            => 'Licencia módulo de folios',
           'Description'      => 'La empresa puede configurar los folios de sus documentos.'
       ]);

/***********************************************************************/
/**                                                                    */
/**               Semillas para empresa fejhu                          */
/**                                                                    */
/***********************************************************************/ 
        Company::create([
            'rut' => '77.220.226-0',
            'name' => 'Proyecto Fejhu Ingenierá SpA.',
            'contact' => 'Francisco Rios Castillo',
            'phone' => 949500353,
            'email' => 'dasdsa@dsads.cl',
            'region_id' => 1,
            'commune_id' => 5,
            'address' => 'la direccion'
        ]); 

        foreach (License::all() as $license) {
            CompanyLicense::create([
                'company_id' => 1,
                'license_id' => $license->id
            ]);
        }


       
/***********************************************************************/
/**                                                                    */
/**               Semillas para códigos de paso de ticket              */
/**                                                                    */
/***********************************************************************/ 

    Code::insert([           
        'code'  => 'STEP_TICKET',
        'label' => 'Paso de ticket'            
    ]);
        

        Code::insert([           
            'code'    => 'PENDING',
            'label'   => 'Pendiente',
            'type_id' => Code::where('code', '=', 'STEP_TICKET')->first()->id          
        ]);
        Code::insert([           
            'code'    => 'CANCEL',
            'label'   => 'Cancelado',
            'type_id' => Code::where('code', '=', 'STEP_TICKET')->first()->id          
        ]);
        Code::insert([           
            'code'    => 'BOLETA',
            'label'   => 'Boleta',
            'type_id' => Code::where('code', '=', 'STEP_TICKET')->first()->id          
        ]);
        Code::insert([           
            'code'    => 'FACTURA',
            'label'   => 'Factura',
            'type_id' => Code::where('code', '=', 'STEP_TICKET')->first()->id          
        ]);
        Code::insert([           
            'code'    => 'QUOTATION_ACEPTED',
            'label'   => 'Cotización aceptada',
            'type_id' => Code::where('code', '=', 'STEP_TICKET')->first()->id          
        ]);



/***********************************************************************/
/**                                                                    */
/**               Semillas para perfiles                               */
/**                                                                    */
/***********************************************************************/ 
            Code::insert([           
                'code'             => 'PROFILES',
                'label'            => 'Perfiles de Usuario'            
            ]);
                Code::insert([           
                    'code'    => 'SUDO',
                    'label'   => 'Super Administrador/a',
                    'type_id' => Code::where('code', '=', 'PROFILES')->first()->id          
                ]);
                    User::insert([
                        'rut'        => '17.482.835-0',
                        'name'       => 'Francisco Javier Rios Castillo',
                        'email'      => 'frios@fejhu.cl',
                        'address'    => 'Club Hipico 475',
                        'region_id'  => 1,
                        'commune_id' => 1,
                        'password'   => bcrypt('174828350'),
                        'profile_id' => Code::where('code', '=', 'SUDO')->first()->id,
                        'company_id' => Company::first()->id   
                    ]);

                    User::insert([
                        'rut'        => '18.030.940-3',
                        'name'       => 'Jorge Luis',
                        'email'      => 'jgutierrez@proyectofejhu.cl',
                        'address'    => 'Club Hipico 475',
                        'region_id'  => 1,
                        'commune_id' => 1,
                        'password'   => bcrypt('304903081'),
                        'profile_id' => Code::where('code', '=', 'SUDO')->first()->id,
                        'company_id' => Company::first()->id   
                    ]);

                    User::insert([
                        'rut'        => '14.419.660-0',
                        'name'       => 'Manuel Llanten',
                        'email'      => 'mllanten@proyectofejhu.cl',
                        'address'    => 'Club Hipico 475',
                        'region_id'  => 1,
                        'commune_id' => 1,
                        'password'   => bcrypt('6600'),
                        'profile_id' => Code::where('code', '=', 'SUDO')->first()->id,
                        'company_id' => Company::first()->id   
                    ]);
                Code::insert([           
                    'code'    => 'ADMIN',
                    'label'   => 'Administrador/a',
                    'type_id' => Code::where('code', '=', 'PROFILES')->first()->id          
                ]);
                    User::insert([
                        'rut'        => '13.786.962-4',
                        'name'       => 'Usuario Perfil Administrador',
                        'email'      => 'admin@fejhu.cl',
                        'address'    => 'Direccion 1234',
                        'region_id'  => 1,
                        'commune_id' => 1,
                        'password'   => bcrypt('secret'),
                        'profile_id' => Code::where('code', '=', 'ADMIN')->first()->id,
                        'company_id' => Company::first()->id   
                    ]);
                Code::insert([           
                    'code'    => 'SELLER',
                    'label'   => 'Vendedor/a',
                    'type_id' => Code::where('code', '=', 'PROFILES')->first()->id          
                ]);
                Code::insert([           
                    'code'    => 'CUSTOMER',
                    'label'   => 'Cliente/a',
                    'type_id' => Code::where('code', '=', 'PROFILES')->first()->id          
                ]);                   
                Code::insert([           
                    'code'    => 'LOGISTICS',
                    'label'   => 'Asistente/a de Logística',
                    'type_id' => Code::where('code', '=', 'PROFILES')->first()->id          
                ]);
                    User::insert([
                        'rut'        => '13.786.962-4',
                        'name'       => 'Usuario Perfil Asistente de Logistica',
                        'email'      => 'logistica@fejhu.cl',
                        'address'    => 'Direccion 1234',
                        'region_id'  => 1,
                        'commune_id' => 1,
                        'password'   => bcrypt('secret'),
                        'profile_id' => Code::where('code', '=', 'LOGISTICS')->first()->id,
                        'company_id' => Company::first()->id   
                    ]);
                Code::insert([           
                    'code'    => 'ATM',
                    'label'   => 'Cajero/a',
                    'type_id' => Code::where('code', '=', 'PROFILES')->first()->id          
                ]);
               

/***********************************************************************/
/**                                                                    */
/**               Semillas para menus                                  */
/**               y sus licencias asociadas                            */
/**                                                                    */
/***********************************************************************/ 

            // Menu::create([
            //     'name'        => 'Inicio',
            //     'code'        => 'INIT_GM',
            //     'description' => 'Panel de inicio',
            //     'path_icon'   => 'fas fa-tachometer-alt',
            //     'route'       => '/home',                
            // ]);
            //     LicenseMenu::create([
            //         'license_id' => License::where('code', '=', 'L_M_GEO_DASH')->first()->id,
            //         'menu_id' => Menu::where('code', '=', 'INIT_GM')->first()->id,
            //     ]);
            Menu::create([
                'name'        => 'Escritorio',
                'description' => 'Escritorio',
                'code'        => 'INIT_DS',
                'path_icon'   => 'fas fa-tachometer-alt',
                'route'       => '/dashboard',
            ]);
                LicenseMenu::create([
                    'license_id' => License::where('code', '=', 'L_M_DASH_STADISTIC')->first()->id,
                    'menu_id' => Menu::where('code', '=', 'INIT_DS')->first()->id,
                ]);

            Menu::create([
                'name'        => 'Administración',
                'description' => 'Módulos para gestión de sistema',
                'code'        => 'ADMIN',
                'path_icon'   => 'fab fa-ethereum',
                'route'       => '#',
            ]);

            Menu::create([
                'name'        => 'Super Administración',
                'code'        => 'SUDO',
                'description' => 'Módulos para la super gestión de sistema',
                'path_icon'   => 'fas fa-gem',
                'route'       => '#',
            ]);

            Menu::create([
                'name'        => 'Bodega',
                'code'        => 'BODEGA',
                'description' => 'Módulos para gestión de bodega',
                'path_icon'   => 'fas fa-ethernet',
                'route'       => '#',
            ]);

            Menu::create([
                'name'        => 'Ventas',
                'description' => 'Panel de inicio',
                'path_icon'   => 'fas fa-donate',
                'code'        => 'SALES',
                'route'       => '#',
            ]);

            Menu::create([
                'name'        => 'Reportes',
                'description' => 'Reportes de la empresa',
                'path_icon'   => 'fas fa-book-open',
                'code'        => 'REPORTS',
                'route'       => '#',
            ]);

                Menu::create([
                    'name'        => 'Reporte stock critico',
                    'description' => 'Reporte para la ver productos con stock crítico',
                    'path_icon'   => 'fas fa-book-open',
                    'route'       => '/report/company-critical-stock',
                    'code'        => 'M_R_CRITICAL_STOCK',
                    'parent_id'   =>  Menu::where('code', '=', 'REPORTS')->first()->id
                ]);

                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_R_CRITIKL_STOCK')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'REPORTS')->first()->id,
                    ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_R_CRITIKL_STOCK')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'M_R_CRITICAL_STOCK')->first()->id,
                    ]);
           

            Menu::create([
                'name'        => 'Mi Perfil',
                'description' => 'Módulo para ver mi perfil',
                'path_icon'   => 'fas fa-award',
                'route'       => '/my-profile',
                'code'        => 'MY_PROFILE',
            ]);
           
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_MY_PROFILE')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'MY_PROFILE')->first()->id,
                    ]);
            
                  
                Menu::create([
                    'name'        => 'Emisión de Ticket',
                    'description' => 'Modulo para la emisión de ticket de venta',
                    'path_icon'   => 'fas fa-cash-register',
                    'route'       => '/ticket-register',
                    'code'        => 'EMIT_TICKET',
                    'parent_id'   =>  Menu::where('code', '=', 'SALES')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_SALES_TICKET')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SALES')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_SALES_TICKET')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'EMIT_TICKET')->first()->id,
                        ]);

                Menu::create([
                    'name'        => 'Gestión de ventas',
                    'description' => 'Módulo para la gestión de ventas',
                    'path_icon'   => 'fas fa-cash-register',
                    'route'       => '/sales',
                    'code'        => 'SALES_LIST',
                    'parent_id'   =>  Menu::where('code', '=', 'SALES')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_SALES_LIST')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SALES')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_SALES_LIST')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SALES_LIST')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Configuración de Folios',
                    'description' => 'Módulo para la configuración de folios de documentos.',
                    'path_icon'   => 'fas fa-book',
                    'route'       => '/folios',
                    'code'        => 'CONF_FOLIOS',
                    'parent_id'   =>  Menu::where('code', '=', 'SALES')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_FOLIOS_CONFIG')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SALES')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_FOLIOS_CONFIG')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'CONF_FOLIOS')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Productos de la empresa',
                    'description' => 'Módulo para la gestión de productos en asignados a la empresa',
                    'path_icon'   => 'fas fa-boxes',
                    'route'       => '/products-company',
                    'code'        => 'MULT_CONF_PROD',
                    'parent_id'   =>  Menu::where('code', '=', 'BODEGA')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_PRODUCT_COMPANY')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'BODEGA')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_PRODUCT_COMPANY')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'MULT_CONF_PROD')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Recepción de Productos',
                    'description' => 'Módulo para la gestión de productos en asignados a la empresa',
                    'path_icon'   => 'fas fa-clipboard-check',
                    'route'       => '/receptions',
                    'code'        => 'RECEPTIONS',
                    'parent_id'   =>  Menu::where('code', '=', 'BODEGA')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_RECEPTIONS')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'BODEGA')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_RECEPTIONS')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'RECEPTIONS')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Inventario de Productos',
                    'description' => 'Módulo para la gestión de productos en asignados a la empresa',
                    'path_icon'   => 'fas fa-clipboard-list',
                    'route'       => '/inventories',
                    'code'        => 'INVENTORIES',
                    'parent_id'   =>  Menu::where('code', '=', 'BODEGA')->first()->id
                ]);  
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_INVENTORIES')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'BODEGA')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_INVENTORIES')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'INVENTORIES')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Gestion de perfiles',
                    'description' => 'Módulo para la creación de nuevos perfiles',
                    'path_icon'   => 'fas fa-theater-masks',
                    'route'       => '/profiles',
                    'code'        => 'PROFILES',
                    'parent_id'   =>  Menu::where('code', '=', 'SUDO')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_PROFILES')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SUDO')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_PROFILES')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'PROFILES')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Gestión de Marcas',
                    'description' => 'Módulo papra la gestión de marcas',
                    'path_icon'   => 'fas fa-certificate',
                    'route'       => '/brands',
                    'code'        => 'BRANDS',
                    'parent_id'   =>  Menu::where('code', '=', 'ADMIN')->first()->id

                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_BRANDS')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'ADMIN')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_BRANDS')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'BRANDS')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Gestión de usuarios empresa',
                    'description' => 'Módulo para la gestión de registos de usuarios en el sistema',
                    'path_icon'   => 'fas fa-users',
                    'route'       => '/users-company',
                    'code'        => 'USERS_COMPANY',
                    'parent_id'   =>  Menu::where('code', '=', 'SUDO')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_USER_COMPANY')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SUDO')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_USER_COMPANY')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'USERS_COMPANY')->first()->id,
                        ]);
                Menu::create([
                    'name'        => 'Gestión de usuarios',
                    'description' => 'Módulo para la gestión de registos de usuarios en el sistema',
                    'path_icon'   => 'fas fa-users',
                    'route'       => '/users',
                    'code'        => 'USERS',
                    'parent_id'   =>  Menu::where('code', '=', 'SUDO')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_USER')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SUDO')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_USER')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'USERS')->first()->id,
                        ]);

                Menu::create([
                    'name'        => 'Configuración de Productos',
                    'description' => 'Módulo para la gestión de productos',
                    'path_icon'   => 'fas fa-boxes',
                    'route'       => '/mono-company-products',
                    'code'        => 'MONO_CONF_PROD',
                    'parent_id'   =>  Menu::where('code', '=', 'BODEGA')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_PRODUCT_CONFIG')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'BODEGA')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_PRODUCT_CONFIG')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'MONO_CONF_PROD')->first()->id,
                        ]);

                Menu::create([
                    'name'        => 'Gestión de Menus',
                    'description' => 'Módulo para la gestión de registos de menus en el sistema',
                    'path_icon'   => 'fas fa-users',
                    'route'       => '/menus',
                    'code'        => 'CONF_MENUS',
                    'parent_id'   =>  Menu::where('CODE', '=', 'SUDO')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_MENUS')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SUDO')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_MENUS')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'CONF_MENUS')->first()->id,
                        ]);

                Menu::create([
                    'name'        => 'Gestión de Licencias',
                    'description' => 'Módulo para la gestión de Licencias',
                    'path_icon'   => 'fas fa-dragon',
                    'route'       => '/licenses',
                    'code'        => 'CONF_LICENSES',
                    'parent_id'   =>  Menu::where('CODE', '=', 'SUDO')->first()->id
                ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_LICENSES')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'SUDO')->first()->id,
                        ]);
                        LicenseMenu::create([
                            'license_id' => License::where('code', '=', 'L_M_LICENSES')->first()->id,
                            'menu_id' => Menu::where('code', '=', 'CONF_LICENSES')->first()->id,
                        ]);

              
                Menu::create([
                    'name'        => 'Gestión de Empresas',
                    'description' => 'Módulo para la gestión de registos de Empresas en el sistema',
                    'path_icon'   => 'fas fa-building',
                    'route'       => '/companies',
                    'code'        => 'COMPANIES',
                    'parent_id'   =>  Menu::where('code', '=', 'SUDO')->first()->id
                ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_COMPANY')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'SUDO')->first()->id,
                    ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_COMPANY')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'COMPANIES')->first()->id,
                    ]);

                Menu::create([
                    'name'        => 'Gestión de Productos',
                    'description' => 'Módulo para la gestión de registos de productos en el sistema',
                    'path_icon'   => 'fas fa-dolly-flatbed',
                    'route'       => '/products',
                    'code'        => 'MULTI_PROD',
                    'parent_id'   =>  Menu::where('code', '=', 'ADMIN')->first()->id
                ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_PRODUCT')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'ADMIN')->first()->id,
                    ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_PRODUCT')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'MULTI_PROD')->first()->id,
                    ]);
                
                Menu::create([
                    'name'        => 'Gestión de proveedores',
                    'description' => 'Módulo para la gestión de registos de proveedores en el sistema',
                    'path_icon'   => 'fas fa-handshake',
                    'route'       => '/providers',
                    'code'        => 'PROVIDERS',
                    'parent_id'   =>  Menu::where('code', '=', 'ADMIN')->first()->id
                ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_PROVIDERS')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'ADMIN')->first()->id,
                    ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_PROVIDERS')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'PROVIDERS')->first()->id,
                    ]);

                Menu::create([
                    'name'        => 'Gestión de clientes',
                    'description' => 'Módulo para la gestión de registos de clientes en el sistema',
                    'path_icon'   => 'fas fa-handshake',
                    'route'       => '/customers',
                    'code'        => 'CUSTOMERS',
                    'parent_id'   =>  Menu::where('code', '=', 'ADMIN')->first()->id
                ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_CUSTOMERS')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'ADMIN')->first()->id,
                    ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_CUSTOMERS')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'CUSTOMERS')->first()->id,
                    ]);
                Menu::create([
                    'name'        => 'Gestión de Categorías',
                    'description' => 'Módulo para la gestión de categorías de productos',
                    'path_icon'   => 'fas fa-pallet',
                    'route'       => '/categories',
                    'code'        => 'CATEGORIES',
                    'parent_id'   =>  Menu::where('code', '=', 'ADMIN')->first()->id
                ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_CATEGORIES')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'ADMIN')->first()->id,
                    ]);
                    LicenseMenu::create([
                        'license_id' => License::where('code', '=', 'L_M_CATEGORIES')->first()->id,
                        'menu_id' => Menu::where('code', '=', 'CATEGORIES')->first()->id,
                    ]);

/***********************************************************************/
/**                                                                    */
/**               Semillas menus del perfil sudo                       */
/**                                                                    */
/***********************************************************************/ 

            $menus = Menu::pluck('id')->toArray();
            $sudo  = Code::where('code', '=', 'SUDO')->first();
            $sudo->menu()->attach($menus);

/***********************************************************************/
/**                                                                    */
/**               Semillas para códigos de recepción                   */
/**                                                                    */
/***********************************************************************/ 

        Code::insert([           
            'code'  => 'RECEPTIONS',
            'label' => 'Tipo de recepción'            
        ]);
            Code::insert([           
                'code'    => 'OPEN',
                'label'   => 'Recepción abierto',
                'type_id' => Code::where('code', '=', 'RECEPTIONS')->first()->id          
            ]);
            Code::insert([           
                'code'    => 'CLOSED',
                'label'   => 'Recepción cerrado',
                'type_id' => Code::where('code', '=', 'RECEPTIONS')->first()->id          
            ]);

/***********************************************************************/
/**                                                                    */
/**               Semillas para códigos de inventario                  */
/**                                                                    */
/***********************************************************************/ 

    Code::insert([           
        'code'  => 'INVENTORIES',
        'label' => 'Tipo de recepción'            
    ]);
        Code::insert([           
            'code'    => 'OPEN',
            'label'   => 'Inventario abierto',
            'type_id' => Code::where('code', '=', 'INVENTORIES')->first()->id          
        ]);
        Code::insert([           
            'code'    => 'CLOSED',
            'label'   => 'Inventario cerrado',
            'type_id' => Code::where('code', '=', 'INVENTORIES')->first()->id          
        ]);        

/***********************************************************************/
/**                                                                    */
/**               Semillas para marcas                                 */
/**                                                                    */
/***********************************************************************/ 

            Code::insert([           
                'code'  => 'PRODUCT_BRAND',
                'label' => 'Marcas de productos'            
            ]);
                Code::insert([           
                    'code'    => 'MARCA1',
                    'label'   => 'Marca 1',
                    'type_id' => Code::where('code', '=', 'PRODUCT_BRAND')->first()->id          
                ]);
                Code::insert([           
                    'code'    => 'MARCA2',
                    'label'   => 'Marca 2',
                    'type_id' => Code::where('code', '=', 'PRODUCT_BRAND')->first()->id          
                ]);
                Code::insert([           
                    'code'    => 'MARCA3',
                    'label'   => 'Marca 3',
                    'type_id' => Code::where('code', '=', 'PRODUCT_BRAND')->first()->id          
                ]);  

/***********************************************************************/
/**                                                                    */
/**               Semillas para categorías                             */
/**                                                                    */
/***********************************************************************/ 

            Code::insert([           
                'code'  => 'PRODUCT_CATEGORY',
                'label' => 'Categorías de productos'            
            ]);
                Code::insert([           
                    'code'    => 'CAT1',
                    'label'   => 'Categoría 1',
                    'type_id' => Code::where('code', '=', 'PRODUCT_CATEGORY')->first()->id          
                ]);
                    Code::insert([           
                        'code'    => 'SCAT1',
                        'label'   => 'SubCategoría 1',
                        'type_id' => Code::where('code', '=', 'CAT1')->first()->id          
                    ]);
                    Code::insert([           
                        'code'    => 'SCAT4',
                        'label'   => 'SubCategoría 4',
                        'type_id' => Code::where('code', '=', 'CAT1')->first()->id          
                    ]);
                Code::insert([           
                    'code'    => 'CAT2',
                    'label'   => 'Categoría 2',
                    'type_id' => Code::where('code', '=', 'PRODUCT_CATEGORY')->first()->id          
                ]);
                    Code::insert([           
                        'code'    => 'SCAT2',
                        'label'   => 'SubCategoría 2',
                        'type_id' => Code::where('code', '=', 'CAT2')->first()->id          
                    ]);
                    Code::insert([           
                        'code'    => 'SCAT3',
                        'label'   => 'SubCategoría 3',
                        'type_id' => Code::where('code', '=', 'CAT2')->first()->id          
                    ]);

        
                
/***********************************************************************/
/**                                                                    */
/**               Semillas para productos                              */
/**                                                                    */
/***********************************************************************/ 

            Product::create([
                'barcode'     => '9788496975101',
                'name'        => 'Tratado Elemental de Ciencia Oculta',
                'description' => 'El estudio del ocultismo, que lleva a cabo Papus, es mucho más que una simple aventura del espíritu. El autor traspone los estrechos ámbitos de una común curiosidad por lo arcano y proporciona atinadas respuestas a múltiples fenómenos suprafísicos.',
                'category_id' =>  Code::where('code', '=', 'SCAT1')->first()->id,           
                'brand_id'    =>  Code::where('code', '=', 'MARCA1')->first()->id                
            ]); 
            Product::create([
                'barcode'     => '9789563608090',
                'name'        => 'Los Nuevos Brujos',
                'description' => 'El estudio del ocultismo, que lleva a cabo Papus, es mucho más que una simple aventura del espíritu. El autor traspone los estrechos ámbitos de una común curiosidad por lo arcano y proporciona atinadas respuestas a múltiples fenómenos suprafísicos.',
                'category_id' =>  Code::where('code', '=', 'SCAT1')->first()->id,           
                'brand_id'    =>  Code::where('code', '=', 'MARCA1')->first()->id                
            ]); 
            Product::create([
                'barcode'     => '9788418211300',
                'name'        => 'La Mano Negra',
                'description' => 'El estudio del ocultismo, que lleva a cabo Papus, es mucho más que una simple aventura del espíritu. El autor traspone los estrechos ámbitos de una común curiosidad por lo arcano y proporciona atinadas respuestas a múltiples fenómenos suprafísicos.',
                'category_id' =>  Code::where('code', '=', 'SCAT1')->first()->id,           
                'brand_id'    =>  Code::where('code', '=', 'MARCA1')->first()->id                
            ]); 
            Product::create([
                'barcode'     => '9788418211317',
                'name'        => 'Nueva era de la humanidad',
                'description' => 'El estudio del ocultismo, que lleva a cabo Papus, es mucho más que una simple aventura del espíritu. El autor traspone los estrechos ámbitos de una común curiosidad por lo arcano y proporciona atinadas respuestas a múltiples fenómenos suprafísicos.',
                'category_id' =>  Code::where('code', '=', 'SCAT1')->first()->id,           
                'brand_id'    =>  Code::where('code', '=', 'MARCA1')->first()->id                
            ]); 
            Product::create([
                'barcode'     => '9788497647113',
                'name'        => 'El viajero y su sombra',
                'description' => 'EEl hombre que camina dialoga con su sombra, la sombra acompaña al caminante, el caminante acompaña la sombra, de inicio a fin de la obra los diálogos muestran la presencia de la alteridad y del doble .',
                'category_id' =>  Code::where('code', '=', 'SCAT1')->first()->id,           
                'brand_id'    =>  Code::where('code', '=', 'MARCA1')->first()->id                
            ]);            

/***********************************************************************/
/**                                                                    */
/**               Semillas para tipos de stock                         */
/**                                                                    */
/***********************************************************************/ 
    
            Code::insert([           
                'code'  => 'TYPE_STOCKS',
                'label' => 'Tipo de stock para productos'            
            ]);
                Code::insert([           
                    'code'    => 'MERMA',
                    'label'   => 'Merma',
                    'type_id' => Code::where('code', '=', 'TYPE_STOCKS')->first()->id          
                ]);
                Code::insert([           
                    'code'    => 'CRITICO',
                    'label'   => 'Crítico',
                    'type_id' => Code::where('code', '=', 'TYPE_STOCKS')->first()->id          
                ]);

    }
}