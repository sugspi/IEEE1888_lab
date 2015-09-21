CC=gcc
COMMON_OBJS   = ieee1888_XMLgenerator.o ieee1888_XMLparser.o ieee1888_client.o ieee1888_object_factory.o ieee1888_server.o ieee1888_util.o
LFLAG = -lpthread

all:	WT_GW_write generator

WT_GW_write: WT_GW_write.o $(COMMON_OBJS)
	$(CC) $(COMMON_OBJS) WT_GW_write.o -o WT_GW_write $(LFLAG)

generator: generator.o
	$(CC) generator.o -o generator
clean: 
	rm -f *.o  WT_GW_write *~
