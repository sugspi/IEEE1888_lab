#include <stdio.h>
#include <unistd.h>

int main(void)
{
	FILE *rfp, *wfp;
	char *rfname = "WT20130506.csv";
	char *wfname = "WTcsv.csv";

	double vwt,iwt,pwt,qwt,whwt,fwt,pfwt,wd,ws,ewt,efwt;
	char time_stmp[100];

	rfp = fopen(rfname,"r");
	if( rfp == NULL){
		printf("%s can't be opened", rfname);
		return -1;
	}

	
	while(1){

		if(fscanf(rfp,"%[^,],%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf",
		time_stmp,&vwt,&iwt,&pwt,&qwt,&whwt,&fwt,&pfwt,&ws,&wd,&ewt,&efwt) == EOF) break;

		wfp = fopen(wfname,"w");
		if( wfp == NULL){
			printf("%s can't be opened", wfname);
			return -1;
		}

		fprintf(wfp, "%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf\n",
			vwt,iwt,pwt,qwt,whwt,fwt,pfwt,wd,ws,ewt,efwt);

		fclose(wfp);
		sleep(1);
	}

	fclose(rfp);


	return 0;
}
