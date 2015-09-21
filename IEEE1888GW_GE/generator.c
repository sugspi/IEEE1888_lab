#include <stdio.h>
#include <unistd.h>

int main(void)
{
	FILE *rfp, *wfp;
	char *rfname = "GE20140224.csv";
	char *wfname = "GEcsv.csv";

	double vge,ige,pge,qge,whge,fge,pfge,pv,ppmp,qgas,qgas2,tgec,tgeh,qge1,
			qge2,tw2c,tw2h,qw2,qw22,tb2c,tb2h,qb2,qb22,ta2c,ta2h,qa2,qa22,efgg,efgh,efgt;
	char time_stmp[100];

	rfp = fopen(rfname,"r");
	if( rfp == NULL){
		printf("%s can't be opened", rfname);
		return -1;
	}

	
	while(1){

		if(fscanf(rfp,"%[^,],%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf",
						time_stmp,&vge,&ige,&pge,&qge,&whge,&fge,&pfge,&tgec,&tgeh,&qge1,
						&qge2,&tw2c,&tw2h,&qw2,&qw22,&tb2c,&tb2h,&qb2,&qb22,&ta2c,&ta2h,&qa2,&qa22,&qgas,&qgas2,&efgg,&efgh,&efgt,&pv) == EOF) break;

		wfp = fopen(wfname,"w");
		if( wfp == NULL){
			printf("%s can't be opened", wfname);
			return -1;
		}

		ppmp = pv - pge ;

		fprintf(wfp, "%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf,%lf\n",
					vge,ige,pge,qge,whge,fge,pfge,pv,ppmp,qgas,qgas2,tgec,tgeh,qge1,
					qge2,tw2c,tw2h,qw2,qw22,tb2c,tb2h,qb2,qb22,ta2c,ta2h,qa2,qa22,efgg,efgh,efgt);

		fclose(wfp);
		sleep(1);
	}

	fclose(rfp);


	return 0;
}