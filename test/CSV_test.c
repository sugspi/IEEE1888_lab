#include <stdio.h>

int main(void)
{
	FILE *fp;
	char *fname = "csv1.csv";
	int ret;
	double n1,n2,n3;
	float f1,f2;

	fp = fopen(fname, "r");
	if( fp == NULL){
		printf("%s can't be opned ",fname);
		return -1;
	}

	while(fscanf(fp,"%lf,%lf,%lf",&n1,&n2,&n3) != EOF){
		printf("%lf %lf %lf \n",n1,n2,n3);
	}

	fclose(fp);

	return 0;  
}