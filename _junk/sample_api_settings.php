{  
   "Get":{  
      "url_segments":"api/get/members",
      "request_type":"GET",
      "description":"Fetch rows from the table",
      "enableParams": true,
      "authorization":{  
         "roles": [
            "rock star", "Pope", "cool dude", "baker", "butcher"
         ],
         "userIds": [
            3,1,6,9
         ]
      }
   },
   "Find One":{  
      "url_segments":"api/get/members/{id}",
      "request_type":"GET",
      "description":"Fetch one row",
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ],
      "afterHook": "something",
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
   "Exists":{  
      "url_segments":"api/exists/members/{id}",
      "request_type":"GET",
      "description":"Check if instance exists",
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ],
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
   "Count":{  
      "url_segments":"api/count/members",
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
      "url_segments":"api/create/members",
      "request_type":"POST",
      "description":"Insert database record",
      "enableParams": true,
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
   "Insert Batch":{  
      "url_segments":"api/batch/members",
      "request_type":"POST",
      "description":"Insert multiple records",
      "enableParams": true,
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
      "Update":{  
      "url_segments":"api/update/members/{id}",
      "request_type":"PUT",
      "description":"Update a database record",
      "enableParams": true,
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ],
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
   "Destroy":{  
      "url_segments":"api/destroy/members",
      "request_type":"DELETE",
      "description":"Delete row or rows",
      "enableParams": true,
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   },
   "Delete One":{  
      "url_segments":"api/delete/members/{id}",
      "request_type":"DELETE",
      "description":"Delete one row",
      "required_fields": [
        {
           "name": "id",
           "label": "ID" 
        }
      ],
      "authorization":{  
         "roles":[  
            "admin"
         ]
      }
   }
}