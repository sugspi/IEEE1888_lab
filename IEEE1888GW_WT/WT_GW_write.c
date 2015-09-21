#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include "ieee1888.h"


#define MAX_POINT_ID_LEN 1024
#define MAX_CONTENT_LEN 65536
#define REGISTER_COUNT 11

const char *POINT_SET = "http://www.gutp.jp/v1/wt/";

/* sending date into this struct */
struct app_data
{
	char id[MAX_POINT_ID_LEN];
	time_t time;
	char value[MAX_CONTENT_LEN];
};

struct app_data mydata[REGISTER_COUNT];

int loadCSV()
{
	FILE *fp;
	char *fname = "WTcsv.csv";

	char time_stmp[100];
	fp = fopen(fname, "r");
	if( fp == NULL){
		printf("%s can't be opned ",fname);
		return -1;
	}

	fscanf(fp,"%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,]",
		mydata[0].value,mydata[1].value,mydata[2].value,mydata[3].value,mydata[4].value,
		mydata[5].value,mydata[6].value,mydata[7].value,mydata[8].value,mydata[9].value,mydata[10].value);

	printf("%s %s %s %s %s %s %s %s %s %s %s \n",
		mydata[0].value,mydata[1].value,mydata[2].value,mydata[3].value,mydata[4].value,
		mydata[5].value,mydata[6].value,mydata[7].value,mydata[8].value,mydata[9].value,mydata[10].value);

	fclose(fp);  
	return 0;
}

int main(int argc, char** argv){
	int i;

	/* setting of Point ID */
	while(1){
		
		time_t t = time(NULL);
		for(i=0; i<REGISTER_COUNT; i++){
			mydata[i].time = t;
			strcpy(mydata[i].id,POINT_SET);
		}

		strcat(mydata[0].id,"vwt");
		strcat(mydata[1].id,"iwt");
		strcat(mydata[2].id,"pwt");
		strcat(mydata[3].id,"qwt");
		strcat(mydata[4].id,"whwt");
		strcat(mydata[5].id,"fwt");
		strcat(mydata[6].id,"pfwt");
		strcat(mydata[7].id,"wd");
		strcat(mydata[8].id,"ws");
		strcat(mydata[9].id,"ewt");
		strcat(mydata[10].id,"efwt");

		loadCSV();


		/*IEEE object create */
		ieee1888_transport* request = ieee1888_mk_transport();
		request -> body = ieee1888_mk_body();

		ieee1888_pointSet* ps = ieee1888_mk_pointSet_array(1);
		ps -> id = ieee1888_mk_uri("http://www.gutp.jp/v1/wt/");
		request -> body -> pointSet = ps;
		request -> body -> n_pointSet = 1;

		ieee1888_point* p = ieee1888_mk_point_array(REGISTER_COUNT);
		ps -> point = p;
		ps -> n_point = REGISTER_COUNT;

		/*putting into sendint date for object*/
		for(i=0; i<REGISTER_COUNT; i++){
			p[i].id = ieee1888_mk_uri(mydata[i].id);
			p[i].value = ieee1888_mk_value();
			p[i].n_value = 1;
			p[i].value -> time = ieee1888_mk_time(mydata[i].time);
			p[i].value -> content = ieee1888_mk_string(mydata[i].value);
		}

		int err = 0;

		/*display querry and object*/
		ieee1888_dump_objects((ieee1888_object*)request);

		/*connecting with server*/
		ieee1888_transport* response = ieee1888_client_data(request,"http://192.168.2.140/axis2/services/FIAPStorage",NULL,&err);

		/*display response message and objects*/
		ieee1888_dump_objects((ieee1888_object*)response);

		/*success*/
		if(response != NULL){
			if(response -> header -> OK != NULL){
				printf("Data Upload Success\n");
			}else{
			}
		}

		/*delete object in memory*/
		if(request != NULL){
			ieee1888_destroy_objects((ieee1888_object*)request);
			free(request);
		}

		if(response != NULL){
			ieee1888_destroy_objects((ieee1888_object*)response);
			free(response);
		}

		sleep(1);
	}

	return 0;

}