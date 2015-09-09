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

/*                                                                                */
/* ieee1888_sample_app.c                                                          */
/*                                                                                */
/* This sample application FETCH from                                             */
/*   http://fiap-sandbox.gutp.ic.i.u-tokyo.ac.jp/axis2/services/FIAPStorage       */
/*  and, WRITE to                                                                 */
/*   http://fiap-dev.gutp.ic.i.u-tokyo.ac.jp/axis2/services/FIAPStorage           */
/*  for the following points                                                      */
/*   http://gutp.jp/Arduino/test-001/Temperature                                  */
/*   http://gutp.jp/Arduino/test-001/Illumiance                                   */
/*   http://gutp.jp/Arduino/test-001/DIPSW                                        */
/*   http://gutp.jp/Arduino/test-001/TGLSW                                        */
/*  (latest data only)                                                            */
/*                                                                                */
/* author: Hideya Ochiai                                                          */
/* create: 2012-06-18                                                             */
/* update: 2013-10-16                                                             */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <unistd.h>

#include "ieee1888.h"


#define MAX_POINT_ID_LEN 1024
#define MAX_CONTENT_LEN 65536
#define MAX_TIME_LEN 32
#define POINT_COUNT 4

struct point_value {
  char point_id[MAX_POINT_ID_LEN];
  char time[MAX_TIME_LEN];
  char content[MAX_CONTENT_LEN];
  int  content_valid;
};

struct point_value m_buffer[POINT_COUNT];

char m_IEEE1888_FROM[]="http://fiap-sandbox.gutp.ic.i.u-tokyo.ac.jp/axis2/services/FIAPStorage";
char m_IEEE1888_TO[]="http://fiap-dev.gutp.ic.i.u-tokyo.ac.jp/axis2/services/FIAPStorage";

void init(){
  strcpy(m_buffer[0].point_id,"http://gutp.jp/Arduino/test-001/Temperature");
  strcpy(m_buffer[1].point_id,"http://gutp.jp/Arduino/test-001/Illuminance");
  strcpy(m_buffer[2].point_id,"http://gutp.jp/Arduino/test-001/DIPSW");
  strcpy(m_buffer[3].point_id,"http://gutp.jp/Arduino/test-001/TGLSW");
  m_buffer[0].content_valid=0;
  m_buffer[1].content_valid=0;
  m_buffer[2].content_valid=0;
  m_buffer[3].content_valid=0;
}


#define IEEE1888_FETCH_SUCCESS 1
#define IEEE1888_FETCH_FAIL 0
int fetch_from_server(){

  ieee1888_transport* rq_transport=ieee1888_mk_transport();
  ieee1888_header* rq_header=ieee1888_mk_header();
  ieee1888_query* rq_query=ieee1888_mk_query();
  ieee1888_key* rq_key=ieee1888_mk_key_array(4);

  int i;
  for(i=0;i<POINT_COUNT;i++){
    rq_key[i].id=ieee1888_mk_string(m_buffer[i].point_id);
    rq_key[i].attrName=ieee1888_mk_attrNameType("time");
    rq_key[i].select=ieee1888_mk_selectType("maximum");
  }

  rq_query->id=ieee1888_mk_new_uuid();
  rq_query->type=ieee1888_mk_queryType("storage");
  rq_query->key=rq_key;
  rq_query->n_key=4;
  rq_header->query=rq_query;
  rq_transport->header=rq_header;

  int err;
  ieee1888_transport* rs_transport=ieee1888_client_query(rq_transport,m_IEEE1888_FROM,0,&err);

  // Recycle
  ieee1888_destroy_objects((ieee1888_object*)rq_transport);
  free(rq_transport);

  if(rs_transport==NULL){
    return IEEE1888_FETCH_FAIL;
  }

  ieee1888_header* rs_header=rs_transport->header;
  if(rs_header==NULL){
    ieee1888_destroy_objects((ieee1888_object*)rs_transport);
    free(rs_transport);
    return IEEE1888_FETCH_FAIL;
  }

  if(rs_header->OK==NULL){
    if(rs_header->error==NULL){
      printf("FATAL: neither OK nor error was put in the response.\n");
    }
    ieee1888_destroy_objects((ieee1888_object*)rs_transport);
    free(rs_transport);
    return IEEE1888_FETCH_FAIL;
  }

  ieee1888_body* rs_body=rs_transport->body;
  if(rs_body!=NULL){
    ieee1888_point* rs_point=rs_body->point;
    int rs_n_point=rs_body->n_point;
    if(rs_n_point>0 && rs_point!=NULL){
      int i,k;
      for(i=0;i<rs_n_point;i++){
        for(k=0;k<POINT_COUNT;k++){
          if(strcmp(m_buffer[k].point_id,rs_point[i].id)==0){
	    ieee1888_value* rs_value=rs_point[i].value;
	    int rs_n_value=rs_point[i].n_value;

            if(rs_n_value>0 && rs_value!=NULL){
               ieee1888_value* target_value=&rs_value[rs_n_value-1];
	       strncpy(m_buffer[k].time,target_value->time,MAX_TIME_LEN);
	       strncpy(m_buffer[k].content,target_value->content,MAX_CONTENT_LEN);
	       m_buffer[k].content_valid=1;
	    }
	    break;
	  }
	}
      }
    }
  }
  ieee1888_destroy_objects((ieee1888_object*)rs_transport);
  free(rs_transport);

  return IEEE1888_FETCH_SUCCESS;
}

#define IEEE1888_WRITE_SUCCESS 1
#define IEEE1888_WRITE_FAIL 0
int write_to_server(){

   ieee1888_transport* rq_transport=ieee1888_mk_transport();
   ieee1888_body* rq_body=ieee1888_mk_body();
   ieee1888_point* rq_point=ieee1888_mk_point_array(POINT_COUNT);
   
   int i,n;
   for(i=0,n=0;i<POINT_COUNT;i++){
     if(m_buffer[i].content_valid){
       ieee1888_value* rq_value=ieee1888_mk_value();
       rq_value->time=ieee1888_mk_time_from_string(m_buffer[i].time);
       rq_value->content=ieee1888_mk_string(m_buffer[i].content);
       rq_point[n].id=ieee1888_mk_uri(m_buffer[i].point_id);
       rq_point[n].value=rq_value;
       rq_point[n].n_value=1;
       n++;
     }
   }
   
   rq_body->point=rq_point;
   rq_body->n_point=n;
   rq_transport->body=rq_body;

   // for Debug
   // ieee1888_dump_objects((ieee1888_object*)rq_transport);

   int err;
   ieee1888_transport* rs_transport=ieee1888_client_data(rq_transport,m_IEEE1888_TO,0,&err);

   // Recycle
   ieee1888_destroy_objects((ieee1888_object*)rq_transport);
   free(rq_transport);

   if(rs_transport==NULL){
     return IEEE1888_WRITE_FAIL;
   }

   ieee1888_header* rs_header=rs_transport->header;
   if(rs_header==NULL){
     ieee1888_destroy_objects((ieee1888_object*)rs_transport);
     free(rs_transport);
     return IEEE1888_WRITE_FAIL;
   }

   if(rs_header->OK==NULL){
     if(rs_header->error==NULL){
       printf("FATAL: neither OK nor error was put in the response.\n");
     }
     ieee1888_destroy_objects((ieee1888_object*)rs_transport);
     free(rs_transport);
     return IEEE1888_WRITE_FAIL;
   }
  
   ieee1888_destroy_objects((ieee1888_object*)rs_transport);
   free(rs_transport);

   return IEEE1888_WRITE_SUCCESS;
}

int main(int argc,char** argv){
  
  init();
  while(1){
    if(fetch_from_server()==IEEE1888_FETCH_SUCCESS){
      if(write_to_server()==IEEE1888_WRITE_SUCCESS){
        printf("Copy -- Success !(^o^)!\n");
      }
    }
    sleep(30);
  }
  return 1;
}


