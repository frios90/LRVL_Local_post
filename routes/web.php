<?php //b4f2e6d787e3632e35b6465fb958eef5

Route::get('/',      'Auth\LoginController@login');
Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@login']);

Route::post('/sing-in',  [ 'as' => '/sing-in', 'uses' => 'Auth\LoginController@singIn']);
Route::post('/log-out',  [ 'as' => '/log-out', 'uses' => 'Auth\LoginController@logOut']);
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'intranet'], function () {

/***********************************************************************/
/**                                                                    */
/**               rutas de para home                                   */
/**                                                                    */
/***********************************************************************/ 

        // Route::get('/home',            'HomeController@index')->name('home');
        Route::get('/dashboard', 'HomeController@index')->name('/dashboard');
        Route::get('/get-main-widgets', 'HomeController@getMainWidget')->name('/get-main-widgets');
/***********************************************************************/
/**                                                                    */
/**               rutas de para usuario                                */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/users',                                  'UserController@index');
        Route::get('/get-user',                               'UserController@get')->name('/get-user');
        Route::get('/get-user-for-rut',                       'UserController@getRut')->name('/get-user-for-rut');
        Route::get('/users/create',                           'UserController@index');
        Route::get('/users/edit/{id}',                        'UserController@index');
        Route::get('/users/geolocation/{id}',                 'UserController@geolocations');
        Route::post('/user-store',                            'UserController@store');
        Route::post('/user-update',                           'UserController@update');
        Route::post('/user-status',                           'UserController@status');
        Route::get('/users-table-list',                       'UserController@getList')->name('/users-table-list');
        Route::get('/companies/{company_id}/users',           'UserController@index');
        Route::get('/companies/{company_id}/users/create',    'UserController@index');
        Route::get('/companies/{company_id}/users/edit/{id}', 'UserController@index');
        Route::get('/users-company-table-list',               'UserController@getUserCompanyList')->name('/users-company-table-list');
        Route::post('/user-geolocation-store',                'UserController@storeGeoLocation');
        Route::get('/users/upload-photos/{id}',               'UserController@index');
        Route::post('/user-delete-photo',                     'UserController@postDeletePhoto');
        Route::post('/user-store-photo',                      'UserController@storePhoto');
        Route::get('/get-user-to-upload-photos',              'UserController@getUserToUploadPhotos')->name('/get-user-to-upload-photos');
        Route::post('/user-principal-photo',                  'UserController@postPrincipalPhoto');
        Route::get('/user-data-auth',                         'UserController@dataAuth');

        Route::get('/users-company',                                  'UserCompanyController@index');
        Route::get('/users-company/create',                           'UserCompanyController@index');
        Route::get('/users-company/edit/{id}',                        'UserCompanyController@index');
        Route::get('/users-company/geolocation/{id}',                 'UserCompanyController@geolocations');
        Route::get('/users-mono-company-table-list',                       'UserCompanyController@getList')->name('/users-mono-company-table-list');
        Route::get('/users-company/upload-photos/{id}',               'UserCompanyController@index');
/***********************************************************************/
/**                                                                    */
/**               rutas de para códigos                                */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/codes',                  'CodeController@index');
        Route::get('/get-code',               'CodeController@get')->name('/get-code');
        Route::get('/codes-table-list',       'CodeController@getList')->name('/codes-table-list');
        Route::get('/get-code-list-for-type', 'CodeController@getListForType')->name('/get-code-list-for-type');
        Route::get('/get-profiles-not-sudo',  'CodeController@getListForTypeProfileSudoFalse')->name('/get-profiles-not-sudo');
        Route::get('/codes/create',           'CodeController@index');
        Route::get('/codes/edit/{id}',        'CodeController@index');
        Route::post('/code-store',            'CodeController@store');
        Route::post('/code-status',           'CodeController@status'); 

/***********************************************************************/
/**                                                                    */
/**               rutas de para empresa                                */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/companies',                   'CompanyController@index');
        Route::get('/get-company',                 'CompanyController@get')->name('/get-company');
        Route::get('/companies/create',            'CompanyController@index');
        Route::get('/companies/edit/{id}',         'CompanyController@index');
        Route::get('/companies/{id}/licenses',     'CompanyController@index');
        Route::get('/companies/geolocation/{id}',  'CompanyController@geolocations');
        Route::post('/company-store',              'CompanyController@store');
        Route::post('/company-update',             'CompanyController@update');
        Route::post('/company-status',             'CompanyController@status');
        Route::get('/companies-table-list',        'CompanyController@getList')->name('/companies-table-list');
        Route::post('/company-geolocation-store',  'CompanyController@storeGeoLocation');
        Route::get('/company-licenses-format-box', 'CompanyController@getLicencesFormatBox')->name('/company-licenses-format-box');
        Route::post('/company-license-store',      'CompanyController@storeLicenses');
        Route::get('/get-companies-list-selector', 'CompanyController@getListSelector');

/***********************************************************************/
/**                                                                    */
/**               rutas de para regiones                                */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/region-list', 'RegionController@getList')->name('/region-list');  

/***********************************************************************/
/**                                                                    */
/**               rutas de para comunas                                */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/commune-list', 'CommuneController@getList')->name('/commune-list');
        
/***********************************************************************/
/**                                                                    */
/**               rutas de para menus                                */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/menus-for-menu',          'MenuController@getMenus');
        Route::get('/menus-parent',            'MenuController@getListParent');
        Route::get('/get-menus-list-selector', 'MenuController@getListSelector');
        Route::get('/menus',                   'MenuController@index');
        Route::get('/get-menu',                'MenuController@get')->name('/get-menu');
        Route::get('/menus/create',            'MenuController@index');
        Route::get('/menus/edit/{id}',         'MenuController@index');
        Route::post('/menu-store',             'MenuController@store');
        Route::post('/menu-update',            'MenuController@update');
        Route::post('/menu-status',            'MenuController@status');
        Route::post('/menu-truncate',          'MenuController@truncate');
        Route::get('/menus-table-list',        'MenuController@getList')->name('/menus-table-list');
    });

        




/***********************************************************************/
/**                                                                    */
/**               rutas de para productos                              */
/**                                                                    */
/***********************************************************************/

        Route::get('/products',                                  'ProductController@index');
        Route::get('/get-product',                               'ProductController@get')->name('/get-product');
        Route::get('/products/create',                           'ProductController@index');
        Route::get('/products/edit/{id}',                        'ProductController@index');
        Route::post('/product-store',                            'ProductController@store');
        Route::post('/product-update',                           'ProductController@update');
        Route::post('/product-status',                           'ProductController@status');
        Route::get('/products-table-list',                       'ProductController@getList')->name('/products-table-list');
        Route::get('/get-categories-subcategories',              'ProductController@getCategorySubcategories')->name('/get-categories-subcategories');
        Route::get('/companies/{company_id}/product-allocation', 'ProductController@index');
        Route::get('/get-products-to-allocation',                'ProductController@getProductToAllocation')->name('/get-products-to-allocation');
        Route::post('/company-product-allocation-store',         'ProductController@companyProductAllocationStore');
        Route::get('/products/upload-photos/{id}',               'ProductController@index');
        Route::post('/product-delete-photo',                     'ProductController@postDeletePhoto');
        Route::post('/product-store-photo',                      'ProductController@storePhoto');
        Route::get('/get-product-to-upload-photos',              'ProductController@getProductToUploadPhotos')->name('/get-product-to-upload-photos');
        Route::post('/product-principal-photo',                  'ProductController@postPrincipalPhoto');
        Route::post('/company-product-allocation-all-store',         'ProductController@companyProductAllocationAllStore');

/***********************************************************************/
/**                                                                    */
/**               rutas de para perfiles                               */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/profiles',             'ProfileController@index');
        Route::get('/get-profile',          'ProfileController@get')->name('/get-profile');
        Route::get('/profiles/menus/{id}',  'ProfileController@index');
        Route::get('/get-menus-format-box-for-profile', 'ProfileController@getMenuFormatBox')->name('/get-menus-format-box-for-profile');;
        Route::post('/profile-store',       'ProfileController@store');
        Route::post('/profile-menus-store', 'ProfileController@storeMenus');
        Route::post('/profile-update',      'ProfileController@update');
        Route::post('/profile-status',      'ProfileController@status');
        Route::get('/profiles-table-list',  'ProfileController@getList')->name('/profiles-table-list');

/***********************************************************************/
/**                                                                    */
/**               rutas de para marcas                                 */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/brands',               'BrandController@index');
        Route::get('/get-brand',            'BrandController@get')->name('/get-brand');
        Route::get('/brands/menus/{id}',    'BrandController@index');
        Route::post('/brand-store',         'BrandController@store');
        Route::post('/brand-update',        'BrandController@update');
        Route::post('/brand-status',        'BrandController@status');
        Route::get('/brands-table-list',    'BrandController@getList')->name('/brands-table-list');

/***********************************************************************/
/**                                                                    */
/**               rutas de para categorias                             */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/categories',                    'CategoryController@index');
        Route::get('/get-category',                  'CategoryController@get')->name('/get-category');
        Route::get('/categories/{id}/subcategories', 'CategoryController@index');
        Route::post('/category-store',               'CategoryController@store');
        Route::post('/category-update',              'CategoryController@update');
        Route::post('/category-status',              'CategoryController@status');
        Route::get('/categories-table-list',         'CategoryController@getList')->name('/categories-table-list');
        Route::post('/category-subcategories-store', 'CategoryController@storeSubcategories');
        Route::get('/subcategories-table-list',      'CategoryController@getSubList')->name('/subcategories-table-list');

/***********************************************************************/
/**                                                                    */
/**               rutas de para licencias                              */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/licenses',            'LicenseController@index');
        Route::get('/get-license',         'LicenseController@get')->name('/get-license');
        Route::get('/licenses/create',     'LicenseController@index');
        Route::get('/licenses/menus/{id}', 'LicenseController@index');
        Route::post('/license-store',      'LicenseController@store');
        Route::post('/license-update',     'LicenseController@update');
        Route::post('/license-status',     'LicenseController@status');
        Route::get('/licenses-table-list', 'LicenseController@getList')->name('/licenses-table-list');
        Route::get('/licenses-for-front',  'LicenseController@getForFront')->name('/licenses-for-front');
        Route::post('/license-menus-store', 'LicenseController@storeMenus');
        Route::get('/get-menus-format-box', 'LicenseController@getMenuFormatBox');

/***********************************************************************/
/**                                                                    */
/**               rutas de para mi perfil                              */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/my-profile',           'MyController@index');
        Route::get('/get-my-profile-data',  'MyController@getMyProfileData');
        Route::post('/my-user-store-photo', 'MyController@storeMyPhoto');
        Route::post('/change-my-info',             'MyController@storeMyInfo');

/***********************************************************************/
/**                                                                    */
/**               rutas de para productos empresa multi                */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/products-company',                             'ProductCompanyController@index');
        Route::get('/get-product-company',                          'ProductCompanyController@get')->name('/get-product-company');
        Route::get('/products-company/create',                      'ProductCompanyController@index');
        Route::get('/products-company/see/{id}',                    'ProductCompanyController@index');
        Route::post('/product-company-status',                      'ProductCompanyController@status');
        Route::get('/products-company-table-list',                  'ProductCompanyController@getList')->name('/products-company-table-list');
        Route::get('/get-categories-subcategories-product-company', 'ProductCompanyController@getCategorySubcategories')->name('/get-categories-subcategories-product-company');
        Route::get('/get-product-company-receptions',               'ProductCompanyController@getProductReception')->name('/get-product-company-reception');
        Route::get('/get-product-company-inventories',              'ProductCompanyController@getProductInventory')->name('/get-product-company-inventory');
        Route::post('/product-company-change-price',                'ProductCompanyController@postChangePrice');
        Route::post('/product-company-change-qty',                  'ProductCompanyController@postChangeQty');
        Route::get('/company-products-to-search-list',              'ProductCompanyController@getProductsAutoSearch')->name('/company-products-to-search-list');
        Route::get('/get-product-to-add-sale',                      'ProductCompanyController@getProductToAddSale')->name('/get-product-to-add-sale');

/***********************************************************************/
/**                                                                    */
/**               rutas de para recepciones                            */
/**                                                                    */
/***********************************************************************/
        Route::get('/receptions',                    'ReceptionController@index');
        Route::get('/get-reception',                 'ReceptionController@getReception')->name('/get-reception');
        Route::get('/get-reception-to-see',          'ReceptionController@getReceptionToSee')->name('/get-reception-to-see');
        Route::get('/receptions/create',             'ReceptionController@index');
        Route::get('/receptions/edit/{id}',          'ReceptionController@index');
        Route::get('/receptions/see/{id}',           'ReceptionController@index');
        Route::post('/reception-store',              'ReceptionController@store');
        Route::post('/reception-update',             'ReceptionController@update');
        Route::post('/reception-status',             'ReceptionController@status');
        Route::get('/receptions-table-list',         'ReceptionController@getList')->name('/receptions-table-list');
        Route::post('/reception-finish',             'ReceptionController@finish');
        Route::post('/reception-update',             'ReceptionController@update');
        Route::post('reception_upload_master',       'ReceptionController@uploadMaster');
        Route::post('/reception-add-item-to-master', 'ReceptionController@addToMaster');
/***********************************************************************/
/**                                                                    */
/**               rutas de para productos proveedores                  */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/providers',              'ProviderController@index');
        Route::get('/get-provider',           'ProviderController@get')->name('/get-provider');
        Route::get('/providers/create',       'ProviderController@index');
        Route::get('/providers/edit/{id}',    'ProviderController@index');
        Route::post('/provider-store',        'ProviderController@store');
        Route::post('/provider-update',       'ProviderController@update');
        Route::post('/provider-status',       'ProviderController@status');
        Route::get('/providers-table-list',   'ProviderController@getList')->name('/providers-table-list');
        Route::get('/get-providers-selector', 'ProviderController@getSelector')->name('/get-proveders-selector');

/***********************************************************************/
/**                                                                    */
/**               rutas de para productos clientes                     */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/customers',              'CustomerController@index');
        Route::get('/get-customer',           'CustomerController@get')->name('/get-customer');
        Route::get('/customers/create',       'CustomerController@index');
        Route::get('/customers/edit/{id}',    'CustomerController@index');
        Route::post('/customer-store',        'CustomerController@store');
        Route::post('/customer-rapid-store',        'CustomerController@rapidStore');

        Route::post('/customer-update',       'CustomerController@update');
        Route::post('/customer-status',       'CustomerController@status');
        Route::get('/customers-table-list',   'CustomerController@getList')->name('/customers-table-list');
        Route::get('/get-customers-selector',   'CustomerController@getSelector')->name('/get-customers-selector');
        Route::get('/customers-to-search-list', 'CustomerController@getCustomersAutoSearch')->name('/customers-to-search-list');
        Route::get('/get-customer-for-rut',     'CustomerController@getRut')->name('/get-customer-for-rut');


  
/***********************************************************************/
/**                                                                    */
/**               rutas de para inventarios                            */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/inventories',                   'InventoryController@index');
        Route::get('/get-inventory',                 'InventoryController@getinventory')->name('/get-inventory');
        Route::get('/inventories/create',            'InventoryController@index');
        Route::get('/inventories/edit/{id}',         'InventoryController@index');
        Route::get('/inventories/see/{id}',          'InventoryController@index');
        Route::post('/inventory-store',              'InventoryController@store');
        Route::post('/inventory-update',             'InventoryController@update');
        Route::post('/inventory-status',             'InventoryController@status');
        Route::get('/inventories-table-list',        'InventoryController@getList')->name('/inventories-table-list');
        Route::post('/inventory-finish',             'InventoryController@finish');
        Route::post('inventory_upload_master',       'InventoryController@uploadMaster');
        Route::post('/inventory-add-item-to-master', 'InventoryController@addToMaster');

/***********************************************************************/
/**                                                                    */
/**               rutas de ticket                                      */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/ticket-register', 'TicketRegisterController@index');          
        Route::post('/store-ticket',   'TicketRegisterController@store');
       

/***********************************************************************/
/**                                                                    */
/**               rutas de ventas                                      */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/sales',                   'SaleController@index');                       
        Route::get('/sales-table-list',        'SaleController@getList')->name('/sales-table-list');
        Route::get('/get-sale',                'SaleController@getSale')->name('/get-sale');
        Route::post('/sale-status',            'SaleController@status');
        Route::get('/sale-pdf-quotation/{id}', 'SaleController@pdfQuotation');
        Route::get('/sales/see/{id}',          'SaleController@index');
        Route::post('/sale-acept-quotation',   'SaleController@postQuotationToSale');
        Route::post('/sale-complete-event',    'SaleController@postSaleEventComplete');


/***********************************************************************/
/**                                                                    */
/**               rutas de para folios                                 */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/folios', 'FolioController@index'); 
        Route::get('/folios-list-to-config', 'FolioController@getListToConfig')->name('/folios-list-to-config');  
        Route::get('/folios-list-selector', 'FolioController@getListSelector')->name('/folios-list-selector');     
        Route::get('/get-current-folio', 'FolioController@getCurrentFolio')->name('/get-current-folio');  
        Route::post('/folio-status',            'FolioController@status');
        Route::post('/folio-store',              'FolioController@store');
        Route::post('/folio-store',             'FolioController@store');
        Route::get('/search-active-folio-for-type', 'FolioController@activeFolioType')->name('/search-active-folio-for-type'); 

/***********************************************************************/
/**                                                                    */
/**               rutas de para productos monoempresa                  */
/**                                                                    */
/***********************************************************************/ 

        Route::get('/mono-company-products',                      'MonoCompanyProductController@index');
        Route::get('/get-mono-company-products',                  'MonoCompanyProductController@get')->name('/get-mono-company-products');
        Route::get('/mono-company-products/create',               'MonoCompanyProductController@index');
        Route::get('/mono-company-products/edit/{id}',            'MonoCompanyProductController@index');
        Route::get('/mono-company-products/cost/{id}',            'MonoCompanyProductController@index');
        Route::post('/mono-company-products-store',               'MonoCompanyProductController@store');
        Route::post('/mono-company-products-update',              'MonoCompanyProductController@update');        
        Route::get('/mono-company-products-table-list',           'MonoCompanyProductController@getList')->name('/mono-company-products-table-list');
        Route::get('/get-categories-subcategories',               'MonoCompanyProductController@getCategorySubcategories')->name('/get-categories-subcategories');
        Route::get('/mono-company-products/upload-photos/{id}',   'MonoCompanyProductController@index');
        Route::post('/mono-company-products-delete-photo',        'MonoCompanyProductController@postDeletePhoto');
        Route::post('/mono-company-products-store-photo',         'MonoCompanyProductController@storePhoto');
        Route::get('/get-mono-company-products-to-upload-photos', 'MonoCompanyProductController@getProductToUploadPhotos')->name('/get-mono-company-products-to-upload-photos');
        Route::post('/mono-company-products-principal-photo',     'MonoCompanyProductController@postPrincipalPhoto');
        Route::get('/mono-company-products/see/{id}',             'MonoCompanyProductController@index');
        Route::post('/mono-company-products-change-price',        'MonoCompanyProductController@postChangePrice');
        Route::post('/mono-company-products-change-qty',          'MonoCompanyProductController@postChangeQty');
        Route::post('/mono-company-products-change-critical-qty', 'MonoCompanyProductController@postChangeCriticalQty');
        Route::post('/mono-company-products-status',              'MonoCompanyProductController@status');
        Route::get('/get-mono-company-products-provider',         'MonoCompanyProductController@getCompanyProductProvider')->name('/get-mono-company-products-provider');
        Route::post('/mono-company-products-provider-cost-store', 'MonoCompanyProductController@postProviderCost');
/***********************************************************************/
/**                                                                    */
/**               rutas de reportes                                    */
/**                                                                    */
/***********************************************************************/ 
        Route::get('/report/company-critical-stock', 'Reports\CompanyProductCriticalStockController@index');
        Route::get('/mono-company-products-critical-stock-table-list', 'Reports\CompanyProductCriticalStockController@getListCriticalStock')->name('/mono-company-products-critical-stock-table-list');

});




