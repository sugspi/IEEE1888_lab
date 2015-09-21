#include <stdio.h>
#include <unistd.h>

int main(void)
{
	FILE *rfp, *wfp;
	char *rfname = "PV20140401.csv";
	char *wfname = "PVcsv.csv";

	double vpv,ipv,ppv,qpv,whpv,fpv,pfpv,pgr,vpdc,ipdc,ppdc,ppac,nkpv,irpv,tpv,ptpv,efpv,efpi,efpt;
	char time_stmp[100];

	rfp = fopen(rfname,"r");
	if( rfp == NULL){
		printf("%s can't be opened", rfname);
		return -1;
	}

	
	while(1){

		if(fscanf(rfp,"%[^,],%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf",
			time_stmp,&vpv,&ipv,&ppv,&qpv,&whpv,&fpv,&pfpv,&pgr,&vpdc,&ipdc,&ppdc,&ppac,&nkpv,
				&irpv,&tpv,&ptpv,&efpv,&efpi,&efpt) == EOF) break;

		wfp = fopen(wfname,"w");
		if( wfp == NULL){
			printf("%s can't be opened", wfname);
			return -1;
		}

		fprintf(wfp, "%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf\n",
			vpv,ipv,ppv,qpv,whpv,fpv,pfpv,irpv,ptpv,tpv,vpdc,ipdc,ppdc,efpv,efpi,efpt);



		fclose(wfp);
		sleep(1);
	}

	fclose(rfp);


	return 0;
}