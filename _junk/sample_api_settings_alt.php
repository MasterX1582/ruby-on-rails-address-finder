{  
   "Get":{  
      "url_segments":"api/get/comments",
      "request_type":"GET",
      "description":"Fetch rows from the table",
      "enableParams": true
   },
   "Find One":{  
      "url_segments":"api/get/comments/{id}",
      "request_type":"GET",
      "description":"Fetch one row",
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ]
   },
   "Exists":{  
      "url_segments":"api/exists/comments/{id}",
      "request_type":"GET",
      "description":"Check if instance exists",
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ]
   },
   "Count":{  
      "url_segments":"api/count/comments",
      "request_type":"GET",
      "description":"Count number of records",
      "enableParams": true,
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
   "Create":{  
      "url_segments":"api/create/comments",
      "request_type":"POST",
      "description":"Insert database record",
      "enableParams": true
   },
   "Insert Batch":{  
      "url_segments":"api/batch/comments",
      "request_type":"POST",
      "description":"Insert multiple records",
      "enableParams": true
   },
      "Update":{  
      "url_segments":"api/update/comments/{id}",
      "request_type":"PUT",
      "description":"Update a database record",
      "enableParams": true,
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ]
   },
   "Destroy":{  
      "url_segments":"api/destroy/comments",
      "request_type":"DELETE",
      "description":"Delete row or rows",
      "enableParams": true
   },
   "Delete One":{  
      "url_segments":"api/delete/comments/{id}",
      "request_type":"DELETE",
      "description":"Delete one row",
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ]
   }
}