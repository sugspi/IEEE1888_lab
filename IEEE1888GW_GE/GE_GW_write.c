#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include "ieee1888.h"


#define MAX_POINT_ID_LEN 1024
#define MAX_CONTENT_LEN 65536
#define REGISTER_COUNT 30

const char *POINT_SET = "http://www.gutp.jp/v2/ge/";

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
	char *fname = "GEcsv.csv";

	char time_stmp[100];
	fp = fopen(fname, "r");
	if( fp == NULL){
		printf("%s can't be opned ",fname);
		return -1;
	}

	fscanf(fp,"%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,],%[^,]",
			mydata[0].value,mydata[1].value,mydata[2].value,mydata[3].value,mydata[4].value,
			mydata[5].value,mydata[6].value,mydata[7].value,mydata[8].value,mydata[9].value,
			mydata[10].value,mydata[11].value,mydata[12].value,mydata[13].value,mydata[14].value,
			mydata[15].value,mydata[16].value,mydata[17].value,mydata[18].value,mydata[19].value,
			mydata[20].value,mydata[21].value,mydata[22].value,mydata[23].value,mydata[24].value,
			mydata[25].value,mydata[26].value,mydata[27].value,mydata[28].value,mydata[29].value);

	printf("%s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s\n",
		mydata[0].value,mydata[1].value,mydata[2].value,mydata[3].value,mydata[4].value,
		mydata[5].value,mydata[6].value,mydata[7].value,mydata[8].value,mydata[9].value,mydata[10].value,
		mydata[11].value,mydata[12].value,mydata[13].value,mydata[14].value,mydata[15].value,mydata[16].value,
		mydata[17].value,mydata[18].value,mydata[19].value,mydata[20].value,mydata[21].value,mydata[22].value,mydata[23].value,mydata[24].value,
		mydata[25].value,mydata[26].value,mydata[27].value,mydata[28].value,mydata[29].value);

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

		strcat(mydata[0].id,"vge");
		strcat(mydata[1].id,"ige");
		strcat(mydata[2].id,"pge");
		strcat(mydata[3].id,"qge");
		strcat(mydata[4].id,"whge");
		strcat(mydata[5].id,"fge");
		strcat(mydata[6].id,"pfge");
		strcat(mydata[7].id,"pv");
		strcat(mydata[8].id,"ppmp");
		strcat(mydata[9].id,"qgas");
		strcat(mydata[10].id,"qgas2");
		strcat(mydata[11].id,"tgec");
		strcat(mydata[12].id,"tgeh");
		strcat(mydata[13].id,"qge1");
		strcat(mydata[14].id,"qge2");
		strcat(mydata[15].id,"tw2c");
		strcat(mydata[16].id,"tw2h");
		strcat(mydata[17].id,"qw2");
		strcat(mydata[18].id,"qw22");
		strcat(mydata[19].id,"tb2c");
		strcat(mydata[20].id,"tb2h");
		strcat(mydata[21].id,"qb2");
		strcat(mydata[22].id,"qb22");
		strcat(mydata[23].id,"ta2c");
		strcat(mydata[24].id,"ta2h");
		strcat(mydata[25].id,"qa2");
		strcat(mydata[26].id,"qa22");
		strcat(mydata[27].id,"efgg");
		strcat(mydata[28].id,"efgh");
		strcat(mydata[29].id,"efgt");

		loadCSV();


		/*IEEE object create */
		ieee1888_transport* request = ieee1888_mk_transport();
		request -> body = ieee1888_mk_body();

		ieee1888_pointSet* ps = ieee1888_mk_pointSet_array(1);
		ps -> id = ieee1888_mk_uri("http://www.gutp.jp/v2/ge/");
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
		ieee1888_transport* response = ieee1888_client_data(request,"http://52.27.198.165/axis2/services/FIAPStorage",NULL,&err);

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