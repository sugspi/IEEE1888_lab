#include <stdio.h>
#include <unistd.h>

int main(void)
{
	FILE *rfp, *wfp;
	char *rfname = "bt20140401.csv";
	char *wfname = "BTcsv.csv";

	double vbt,ibt,pbt,qbt,whbt,fbt,pfbt,vbdc,ibdc,pbdc,efbdc,efbc;
	char time_stmp[100];

	rfp = fopen(rfname,"r");
	if( rfp == NULL){
		printf("%s can't be opened", rfname);
		return -1;
	}

	
	while(1){

		if(fscanf(rfp,"%[^,],%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf",
			time_stmp,&vbt,&ibt,&pbt,&qbt,&whbt,&fbt,&pfbt,&vbdc,&ibdc,&pbdc,&efbdc,&efbc) == EOF) break;

		wfp = fopen(wfname,"w");
		if( wfp == NULL){
			printf("%s can't be opened", wfname);
			return -1;
		}

		fprintf(wfp, "%f,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf\n",
			vbt,ibt,pbt,qbt,whbt,fbt,pfbt,vbdc,ibdc,pbdc,efbdc,efbc);



		fclose(wfp);
		sleep(1);
	}

	fclose(rfp);


	return 0;
}