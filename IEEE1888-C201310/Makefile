CC=gcc
COMMON_OBJS   = ieee1888_XMLgenerator.o ieee1888_XMLparser.o ieee1888_client.o ieee1888_object_factory.o ieee1888_server.o ieee1888_util.o
LFLAG = -lpthread

all:	ieee1888_sample_app ieee1888_sample_gw WT_GW_write

ieee1888_sample_app: ieee1888_sample_app.o $(COMMON_OBJS)
		$(CC) $(COMMON_OBJS) ieee1888_sample_app.o -o ieee1888_sample_app $(LFLAG)

ieee1888_sample_gw: ieee1888_sample_gw.o $(COMMON_OBJS)
		$(CC) $(COMMON_OBJS) ieee1888_sample_gw.o -o ieee1888_sample_gw $(LFLAG)

WT_GW_write: WT_GW_write.o $(COMMON_OBJS)
		$(CC) $(COMMON_OBJS) WT_GW_write.o -o WT_GW_write $(LFLAG)

clean: 
	rm -f *.o ieee1888_sample_gw ieee1888_sample_app WT_GW_write *~
