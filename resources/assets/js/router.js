import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [

      /**Rutas para home */
      {
        path: '/dashboard',
        name: 'dashboard',
        component: require('./components/home/DashAdmin').default        
      },
      {
        path: '/home',
        name: 'home',
        component: require('./components/home/General').default        
      },
     
/**Rutas para usuarios */
        {
          path: '/users',
          name: 'users',
          component: require('./components/users/Index').default        
        },
        {
          path: '/users/create',
          name: 'user.create',
          component: require('./components/users/Create').default        
        },
        {
          path: '/users/edit/:id',
          name: 'user.edit',
          component: require('./components/users/Edit').default        
        }, 
        {
          path: '/users/geolocation/:id',
          name: 'user.geolocation',
          component: require('./components/users/Geolocation').default        
        },  
        {
          path: '/users/upload-photos/:id',
          name: 'user.upload-photos/',
          component: require('./components/users/UploadPhotos').default         
        }, 
/**Rutas para empresas */
        {
          path: '/companies',
          name: 'companies',
          component: require('./components/companies/Index').default        
        },
        {
          path: '/companies/create',
          name: 'company.create',
          component: require('./components/companies/Create').default        
        },
        {
          path: '/companies/edit/:id',
          name: 'company.edit',
          component: require('./components/companies/Edit').default        
        },  

        {
          path: '/companies/:company_id/users',
          name: 'companies.users',
          component: require('./components/companies/users/Index').default        
        },
        {
          path: '/companies/:company_id/users/create',
          name: 'companies.user.create',
          component: require('./components/companies/users/Create').default        
        },
        {
          path: '/companies/:company_id/users/edit/:id',
          name: 'companies.user.edit',
          component: require('./components/companies/users/Edit').default        
        },
        {
          path: '/companies/:company_id/product-allocation',
          name: 'companies.product.allocation',
          component: require('./components/companies/products/Allocation').default        
        },
        
        {
          path: '/companies/geolocation/:id',
          name: 'company.geolocation',
          component: require('./components/companies/Geolocation').default        
        }, 
        {
          path: '/companies/:id/licenses',
          name: 'company.licenses',
          component: require('./components/companies/Licenses').default        
        },  



/**Rutas para perfiles */
        {
          path: '/profiles',
          name: 'profiles',
          component: require('./components/profiles/Index').default       
        },       
        {
          path: '/profiles/menus/:id',
          name: 'profile.menus',
          component: require('./components/profiles/AccessMenus').default         
        }, 

/**Rutas para marcas */
      {
        path: '/brands',
        name: 'brands',
        component: require('./components/brands/Index').default       
      }, 

/**Rutas para licencias */
      {
        path: '/licenses',
        name: 'licenses',
        component: require('./components/licenses/Index').default       
      },
     
      {
        path: '/licenses/menus/:id',
        name: 'licenses.menus',
        component: require('./components/licenses/Menus').default         
      },  
      
/**Rutas para categorias */
      {
        path: '/categories',
        name: 'categories',
        component: require('./components/categories/Index').default       
      },
      {
        path: '/categories/:id/subcategories',
        name: 'category.subcategories',
        component: require('./components/categories/Subcategories').default         
      }, 
        

/**Rutas para menus */
        {
          path: '/menus',
          name: 'menus',
          component: require('./components/menus/Index').default       
        },
        {
          path: '/menus/create',
          name: 'menu.create',
          component: require('./components/menus/Create').default         
        },
        {
          path: '/menus/edit/:id',
          name: 'menu.edit',
          component: require('./components/menus/Edit').default         
        }, 
/**Rutas para mi perfil */
        {
          path: '/my-profile',
          name: 'my-profile',
          component: require('./components/my/Profile').default       
        },



  /**Rutas para recepciones */
        {
          path: '/receptions',
          name: 'receptions',
          component: require('./components/receptions/Index').default       
        },
        {
          path: '/receptions/create',
          name: 'reception.create',
          component: require('./components/receptions/Create').default       
        },
        {
          path: '/receptions/edit/:id',
          name: 'reception.edit',
          component: require('./components/receptions/Edit').default         
        },
        {
          path: '/receptions/see/:id',
          name: 'reception.see',
          component: require('./components/receptions/See').default         
        }, 

/**Rutas para proveedores */
        {
          path: '/providers',
          name: 'providers',
          component: require('./components/providers/Index').default        
        },
        {
          path: '/providers/create',
          name: 'provider.create',
          component: require('./components/providers/Create').default        
        },
        {
          path: '/providers/edit/:id',
          name: 'provider.edit',
          component: require('./components/providers/Edit').default        
        }, 
        {
          path: '/providers/geolocation/:id',
          name: 'provider.geolocation',
          component: require('./components/providers/Geolocation').default        
        },  

/**Rutas para clientes */
        {
          path: '/customers',
          name: 'customers',
          component: require('./components/customers/Index').default        
        },
        {
          path: '/customers/create',
          name: 'customer.create',
          component: require('./components/customers/Create').default        
        },
        {
          path: '/customers/edit/:id',
          name: 'customer.edit',
          component: require('./components/customers/Edit').default        
        },

/**Rutas para inventarios */
        {
          path: '/inventories',
          name: 'inventories',
          component: require('./components/inventories/Index').default       
        },
        {
          path: '/inventories/create',
          name: 'inventory.create',
          component: require('./components/inventories/Create').default       
        },
        {
          path: '/inventories/edit/:id',
          name: 'inventory.edit',
          component: require('./components/inventories/Edit').default         
        },
        {
          path: '/inventories/see/:id',
          name: 'inventory.see',
          component: require('./components/inventories/See').default         
        }, 
/**Rutas para ventas */
        {
          path: '/ticket-register',
          name: 'ticket.register',
          component: require('./components/sales/TicketRegister').default       
        },
        {
          path: '/folios',
          name: 'folios',
          component: require('./components/sales/Folios').default       
        },

        {
          path: '/sales',
          name: 'sales',
          component: require('./components/sales/Index').default       
        },

        {
          path: '/sales/see/:id',
          name: 'sale.edit',
          component: require('./components/sales/See').default         
        },

/**Rutas para monoempresa productos*/
        {
          path: '/mono-company-products',
          name: 'mono-company-products',
          component: require('./components/AreaMonoCompany/Products/Index').default       
        },
        {
          path: '/mono-company-products/create',
          name: 'mono-company-products.create',
          component: require('./components/AreaMonoCompany/Products/Create').default         
        },
        {
          path: '/mono-company-products/edit/:id',
          name: 'mono-company-products.edit',
          component: require('./components/AreaMonoCompany/Products/Edit').default         
        }, 
        {
          path: '/mono-company-products/upload-photos/:id',
          name: 'mono-company-products.upload-photos/',
          component: require('./components/AreaMonoCompany/Products/UploadPhotos').default         
        }, 
        {
          path: '/mono-company-products/see/:id',
          name: 'mono-company-products.see',
          component: require('./components/AreaMonoCompany/Products/See').default         
        }, 
        {
          path: '/mono-company-products/cost/:id',
          name: 'mono-company-products.cost',
          component: require('./components/AreaMonoCompany/Products/PriceProvider').default         
        }, 


             
/**Rutas para empresa usuarios */
        {
          path: '/users-company',
          name: 'users-company',
          component: require('./components/AreaMonoCompany/users/Index').default        
        },
        {
          path: '/users-company/create',
          name: 'usercompany.create',
          component: require('./components/AreaMonoCompany/users/Create').default        
        },
        {
          path: '/users-company/edit/:id',
          name: 'usercompany.edit',
          component: require('./components/AreaMonoCompany/users/Edit').default        
        }, 
        {
          path: '/users-company/geolocation/:id',
          name: 'usercompany.geolocation',
          component: require('./components/AreaMonoCompany/users/Geolocation').default        
        },  
        {
          path: '/users-company/upload-photos/:id',
          name: 'userscompany.upload-photos/',
          component: require('./components/AreaMonoCompany/users/UploadPhotos').default         
        }, 

  /**Ritas de reportes */
        {
          path: '/report/company-critical-stock',
          name: 'report.company-critical-stock',
          component: require('./components/reports/companyProductCriticalStock').default       
        },
      
      
  ]
   
  })