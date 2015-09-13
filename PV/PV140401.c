#include <stdio.h>

int main(void)
{
	FILE *fp;
	char *fname = "PV140401.csv";

	char time_stmp[100];
	float vpv,ipv,ppv,qpv;
	double whpv,fpv,pfpv,pgr,vpdc,ipdc,ppdc,ppac,nkpv,irpv,tpv,ptpv,efpv,efpi,efpt;

	int i = 0;

	fp = fopen(fname, "r");
	if( fp == NULL){
		printf("%s can't be opned ",fname);
		return -1;
	}

	while(fscanf(fp,"%[^,],%f,%f,%f,%f,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf ",time_stmp,&vpv,&ipv,&ppv,&qpv,&whpv,&fpv,&pfpv,&pgr,&vpdc,&ipdc,&ppdc,&ppac,&nkpv,&irpv,&tpv,&ptpv,&efpv,&efpi,&efpt) != EOF){
		
		printf("%d: ",i);
		printf("%s %f,%f,%f,%f,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf ",time_stmp,vpv,ipv,ppv,qpv,whpv,fpv,pfpv,pgr,vpdc,ipdc,ppdc,ppac,nkpv,irpv,tpv,ptpv,efpv,efpi,efpt);
		printf("\n");
		i++;

		//if(i==86400) break;
	}

	printf("count -> %d",i);
	printf("");

	fclose(fp);

	return 0;  
}