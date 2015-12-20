CC=gcc
COMMON_OBJS   = ieee1888_XMLgenerator.o ieee1888_XMLparser.o ieee1888_client.o ieee1888_object_factory.o ieee1888_server.o ieee1888_util.o
LFLAG = -lpthread

all:	PV_GW_hour_write generator

PV_GW_hour_write: PV_GW_hour_write.o $(COMMON_OBJS)
	$(CC) $(COMMON_OBJS) PV_GW_hour_write.o -o PV_GW_hour_write $(LFLAG)

generator: generator.o
	$(CC) generator.o -o generator
clean: 
	rm -f *.o  PV_GW_hour_write *~
