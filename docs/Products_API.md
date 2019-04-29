**Product Listing resolver API**
----
  API returns product listing information for given category

* **URL**

  /categories/{id}/products

* **Method:**
  
  `GET`

* **Access level**
  
    `authorized`, `catalog permissions required`
  
*  **URL Params**
   
      ###Required:
    
      `id=[int]`
      
      ###Optional:
      
      * `filter` - allows filtering product list. 
          #### Format: 
          `filter[{attribute_code}][{operation}]={value}` <br/>
          
           `attribute_code` (string) - attribute code to be filtered <br/>
           `operation` - Optional. Only 'eq' (=) operation is supported,  <br/>
           `value` (string) - Can be an attribute option id. price value. Price range (like `10-`, `10-20`, `20-`)
      
      * `page` - pagination.
           #### Format:
           `page[number]={page_number}&page[size]={products_per_page}` <br/>
           
           `page_number` - int <br/>
           `products_per_page` - int
      
      * `sort`
        #### Format:
        `sort=-{attribute_code}` <br/>
        
        `attribute_code` - string. Attribute for sorting. Prefix with `-` to have descending order
        
   * **Success Response:**
     
     * **Code:** 200 <br />
       **Response fields:**
            Response will contain filter and products objects and product_count field: <br/>
       * filters: array <br/>
        `label`: string. filter label <br/>
        `code`: string. Attribute code to be used as input params <br/>
        `options`: array <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;- `label`: string. Label name <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;- `value`: string. Option value to be used as input value. <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;- `active`: bool. Indicates if filter is already selected <br/>
       * items: array <br/> 
        `id`: int, <br/>
        `sku`: string,<br/>
        `name`: string, <br/>
        `image`: string, full path to magento resized image <br/>
        `url_path`: string, Url path within given category, including `html` suffixes <br/>
        `stock`: <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;- `is_in_stock`: int, defines if product is in stock<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;- `qty`: decimal, (0.00) <br/>
        `price`: <br/>
         &nbsp;&nbsp;&nbsp;&nbsp;- `regular_price`: decimal, <br/>
         &nbsp;&nbsp;&nbsp;&nbsp;- `special_price`: decimal, price with a discount <br/>
         &nbsp;&nbsp;&nbsp;&nbsp;- `min_price`: decimal, minimal available price, for example tier price
       * `total_count`: int
       
     * **Code:** 404<br />
       **Content:** `{ message: "category doesn't exist"}`
    * **Note**: <br/>
      Price are provided as is, i.e. including tax or excluding according to magento configuration.
      
   
