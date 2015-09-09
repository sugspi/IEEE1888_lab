/*
 * Copyright (c) 2013 Hideya Ochiai, the University of Tokyo,  All rights reserved.
 * 
 * Permission of redistribution and use in source and binary forms, 
 * with or without modification, are granted, free of charge, to any person 
 * obtaining the copy of this software under the following conditions:
 * 
 *  1. Any copies of this source code must include the above copyright notice,
 *  this permission notice and the following statement without modification 
 *  except possible additions of other copyright notices. 
 * 
 *  2. Redistributions of the binary code must involve the copy of the above 
 *  copyright notice, this permission notice and the following statement 
 *  in documents and/or materials provided with the distribution.  
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/*                                                   */
/* ieee1888_sample_gw.c                              */
/*                                                   */
/* This sample gateway provides register service at  */
/*      point id="http://gutp.jp/c/sample/reg0"      */
/*      point id="http://gutp.jp/c/sample/reg1"      */
/*      point id="http://gutp.jp/c/sample/reg2"      */
/*      point id="http://gutp.jp/c/sample/reg3"      */
/* You can write and read data from those registers  */
/*  (latest data only)                               */
/*                                                   */
/* author: Hideya Ochiai                             */
/* create: 2012-01-17                                */
/* update: 2013-10-16                                */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#include "ieee1888.h"

#define MAX_POINT_ID_LEN 1024
#define MAX_CONTENT_LEN 65536
#define MAX_TIME_LEN 32
#define REGISTER_COUNT 4

#define POINT_SET "http://gutp.jp/c/sample/"

struct sample_gw_reg {
  char point_id[MAX_POINT_ID_LEN];
  char time[MAX_TIME_LEN];
  char content[MAX_CONTENT_LEN];
  int  content_valid;
};

char sample_gw_pointset[]=POINT_SET;
struct sample_gw_reg m_reg[REGISTER_COUNT];

void init(){
  int i;
  for(i=0;i<REGISTER_COUNT;i++){
    sprintf(m_reg[i].point_id,"%sreg%d",POINT_SET,i);
    m_reg[i].content_valid=0;
  }
}

#define PARSE_POINT_ID_OK   1
#define PARSE_POINT_ID_FAIL 0

int parse_point_id(const char* point_id, int* register_index){
 int i;
 for(i=0;i<REGISTER_COUNT;i++){
   if(strcmp(m_reg[i].point_id,point_id)==0){
     *register_index=i;
     return PARSE_POINT_ID_OK;
   }
 }
 return PARSE_POINT_ID_FAIL;
}

ieee1888_transport* ieee1888_server_query(ieee1888_transport* request, char** args){

  ieee1888_transport* response=(ieee1888_transport*)ieee1888_clone_objects((ieee1888_object*)request,1);
  // TODO: return error if "clone" fails (take compare match)
  
  if(response->body!=NULL){
    ieee1888_destroy_objects((ieee1888_object*)response->body);
    free(response->body);
    response->body=NULL;
  }

  ieee1888_header* header=response->header;
  if(header==NULL){
    response->header=ieee1888_mk_header();
    response->header->error=ieee1888_mk_error_invalid_request("No header in the request.");
    return response;
  }
  if(header->OK!=NULL){
    response->header->error=ieee1888_mk_error_invalid_request("Invalid OK in the header.");
    return response;
  }
  if(header->error!=NULL){
    response->header->error=ieee1888_mk_error_invalid_request("Invalid error in the header.");
    return response;
  }

  ieee1888_query* query=header->query;
  if(header->query==NULL){
    response->header->error=ieee1888_mk_error_invalid_request("No query in the header.");
    return response;
  }

  ieee1888_error* error=NULL;

  if(strcmp(query->type,"storage")==0){

    if(query->callbackData!=NULL){
      // Do nothing (just ignore it)
    }
    if(query->callbackControl!=NULL){
      // Do nothing (just ignore it)
    }

    // Parse the keys in detail
    ieee1888_key* keys=query->key;
    int n_keys=query->n_key;

    ieee1888_point* points=NULL;
    int n_points=0;
    if(n_keys>0){
      points=ieee1888_mk_point_array(n_keys);
      n_points=n_keys;
    }

    int i;
    for(i=0;i<n_keys;i++){
      ieee1888_key* key=&keys[i];
      if(key->id==NULL){
        // error -- invalid id
	error=ieee1888_mk_error_invalid_request("ID is missing in the query key");
        break;

      }else if(key->attrName==NULL){
        // error -- invalid attrName
	error=ieee1888_mk_error_invalid_request("attrName is missing in the query key");
        break;

      }else if(strcmp(key->attrName,"time")!=0){
        // error -- unsupported attrName
	error=ieee1888_mk_error_query_not_supported("attrName other than \"time\" are not supported.");
        break;

      }else if(key->eq!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("eq in the query key is not supported.");
        break;

      }else if(key->neq!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("neq in the query key is not supported.");
        break;

      }else if(key->lt!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("lt in the query key is not supported.");
        break;

      }else if(key->gt!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("gt in the query key is not supported.");
        break;

      }else if(key->lteq!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("lteq in the query key is not supported.");
        break;

      }else if(key->gteq!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("gteq in the query key is not supported.");
        break;

      }else if(key->trap!=NULL){
        // error -- not supported 
	error=ieee1888_mk_error_query_not_supported("trap in the query key is not supported.");
        break;

      }else if(key->select!=NULL && strcmp(key->select,"minimum")!=0 && strcmp(key->select,"maximum")!=0){
        // error -- invalid select
	error=ieee1888_mk_error_invalid_request("Invalid select in the query key.");
        break;

      }else{
        
	int register_index;
	if(parse_point_id(key->id,&register_index)==PARSE_POINT_ID_OK){
	  // generate returning points
	  points[i].id=ieee1888_mk_uri(key->id);
	  if(m_reg[register_index].content_valid){
	    ieee1888_value* v=ieee1888_mk_value();
	    v->time=ieee1888_mk_time_from_string(m_reg[register_index].time);
	    v->content=ieee1888_mk_string(m_reg[register_index].content);
	    points[i].value=v;
	    points[i].n_value=1;
	  }
	}else{
	  // error -- POINT_NOT_FOUND
	  error=ieee1888_mk_error_point_not_found(key->id);
          break;
	}
      }
    }

    if(error==NULL){
      // if no error (return OK)
      response->header->OK=ieee1888_mk_OK();
      response->body=ieee1888_mk_body();
      response->body->point=points;
      response->body->n_point=n_points;
    }else{
      // if error exists (return the error without body)
      response->header->error=error;
      ieee1888_body* body=ieee1888_mk_body();
      body->point=points;
      body->n_point=n_points;
      ieee1888_destroy_objects((ieee1888_object*)body);
      free(body);
    }

  }else if(strcmp(query->type,"stream")==0){
    // not supported
    error=ieee1888_mk_error_query_not_supported("type=\"stream\" in the query is not supported.");
    response->header->error=error;

  }else{
    // error (invalid request)
    error=ieee1888_mk_error_invalid_request("Invalid query type.");
    response->header->error=error;
  }
  return response;
}


ieee1888_error* ieee1888_server_data_parse_request(ieee1888_pointSet* pointSet, int n_pointSet, ieee1888_point* point, int n_point, struct sample_gw_reg *reg){

  int i;
  for(i=0;i<n_pointSet;i++){
    ieee1888_error* error=ieee1888_server_data_parse_request(pointSet[i].pointSet, pointSet[i].n_pointSet, pointSet[i].point, pointSet[i].n_point,reg);
    if(error!=NULL){
      return error;
    }
  }

  for(i=0;i<n_point;i++){
    int register_index;
    if(parse_point_id(point[i].id,&register_index)==PARSE_POINT_ID_OK){
      if(point[i].n_value>0){
        ieee1888_value* v=&(point[i].value[point[i].n_value-1]);
        strncpy(reg[register_index].content,v->content,MAX_CONTENT_LEN);
	if(v->time!=NULL){
          strncpy(reg[register_index].time,v->time,MAX_TIME_LEN);
	}else{
	  char* str_time=ieee1888_mk_time(time(NULL));
          strncpy(reg[register_index].time,str_time,MAX_TIME_LEN);
	  free(str_time);
	}
	reg[register_index].content_valid=1;
      }
    }
  }
  return NULL;
}

ieee1888_error* ieee1888_server_data_commit_request(struct sample_gw_reg *reg){
  memcpy(m_reg,reg,sizeof(struct sample_gw_reg)*REGISTER_COUNT);
  return NULL;
}

//IEEE 1888 data server 
ieee1888_transport* ieee1888_server_data(ieee1888_transport* request,char** args){

  ieee1888_transport* response=ieee1888_mk_transport();

  // TODO: check the validity of body 
  ieee1888_body* body=request->body;

  // Data buffer between parsing and committing the request
  struct sample_gw_reg* reg=(struct sample_gw_reg*)calloc(sizeof(struct sample_gw_reg),REGISTER_COUNT);
  memcpy(reg,m_reg,sizeof(struct sample_gw_reg)*REGISTER_COUNT);



  // parse the "data" request (with preparing committing them), and returns error (only if failed).
  ieee1888_error* error=ieee1888_server_data_parse_request(body->pointSet,body->n_pointSet,body->point,body->n_point,reg);
  if(error!=NULL){
     response->header=ieee1888_mk_header();
     response->header->error=error;
     free(reg);
     return response;
  }

  // commit the "data" request, and returns error (only if failed).
  error=ieee1888_server_data_commit_request(reg);
  if(error!=NULL){
     response->header=ieee1888_mk_header();
     response->header->error=error;

     free(reg);
     return response;
  }

  // return OK, because succeeded.
  response->header=ieee1888_mk_header();
  response->header->OK=ieee1888_mk_OK();
  free(reg);
  return response;
}


int main(int argc,char** argv){
  
  init();
  
  ieee1888_set_service_handlers(ieee1888_server_query,ieee1888_server_data);
  int ret=ieee1888_server_create(1888);

  printf("%d\n",ret);

  return 1;
}


