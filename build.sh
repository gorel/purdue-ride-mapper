SRC=src/
LIBS=lib/
DEST=bin/

javac -d $DEST -cp .:$LIBS $SRC/*.java
