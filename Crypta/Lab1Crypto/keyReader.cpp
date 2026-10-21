#include <iostream>
using namespace std;

int intreadKey() {
	int key;
	printf("key: ");
	scanf_s("%i", &key);
	return key;
}

char* charreadKey() {
	static char key[100];
	printf("key: ");
	if (scanf_s("%99s", key, (unsigned)sizeof(key)) != 1) {
		key[0] = '\0';
	}
	return key;
}
