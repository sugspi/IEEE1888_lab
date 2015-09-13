#include <stdio.h>
#include <stdlib.h>
#include "ieee1888.h"

/* sending date into this struct */
struct app_data
{
	char id[100];
	time_t time;
	char value[100];
};

int main(int argc, char** argv){
	int i;
	struct app_data mydata[3];

	/* setting of Point ID */
	strcpy(mydata[0].id,"http://www.gutp.jp/dummy/point0");
	strcpy(mydata[1].id,"http://www.gutp.jp/dummy/point1");
	strcpy(mydata[2].id,"http://www.gutp.jp/dummy/point2");

	while(1){
		
		time_t t = time(NULL);
		for(i=0; i<3; i++){
			mydata[i].time = t;
			sprintf(mydata[i].value,"%d",rand());
		}

		/*IEEE object create */
		ieee1888_transport* request = ieee1888_mk_transport();
		request -> body = ieee1888_mk_body();

		ieee1888_pointSet* ps = ieee1888_mk_pointSet_array(1);
		ps -> id = ieee1888_mk_uri("http://www.gutp.jp/dummy/");
		request -> body -> pointSet = ps;
		request -> body -> n_pointSet = 1;

		ieee1888_point* p = ieee1888_mk_point_array(3);
		ps -> point = p;
		ps -> n_point = 3;

		/*putting into sendint date for object*/
		for(i=0; i<3; i++){
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
		ieee1888_transport* response = ieee1888_client_data(request,"http://fiap-sandbox.gutp.ic.i.u-tokyo.ac.jp/axis2/services/FIAPStorage",NULL,&err);

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

		sleep(5);
	}

	return 0;

}