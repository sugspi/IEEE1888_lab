CC=gcc
COMMON_OBJS   = ieee1888_XMLgenerator.o ieee1888_XMLparser.o ieee1888_client.o ieee1888_object_factory.o ieee1888_server.o ieee1888_util.o
LFLAG = -lpthread

all:	BT_GW_write generator

BT_GW_write: BT_GW_write.o $(COMMON_OBJS)
	$(CC) $(COMMON_OBJS) BT_GW_write.o -o BT_GW_write $(LFLAG)

generator: generator.o
	$(CC) generator.o -o generator
clean: 
	rm -f *.o  BT_GW_write *~
