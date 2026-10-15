#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "SimpleCesar.h"
#include "vars.h"
#include "keyReader.h"

void Cesar(char* msg) {
    int i, j, key = intreadKey();

    char key_letters[26];
    char new_message[1000] = { 0 };

    for (i = 0; i < (int)strlen(LETTERS); i++) {
        key_letters[i] = LETTERS[(i + key) % 26];
    }

    for (i = 0; msg[i]; i++) {
        for (j = 0; j < (int)strlen(LETTERS); j++) {
            if (msg[i] == LETTERS[j]) {
                new_message[i] = key_letters[j];
                break;
            }
            else {
                new_message[i] = msg[i]; // Сохраняем символ, если его нет в алфавите
            }
        }
    }
    new_message[strlen(msg)] = '\0';

    printf("%s\n", new_message);
}

void CesarDecrypt(char* msg) {
    int i, j, key;
    printf("key: ");
    scanf("%d", &key);

    char key_letters[26];
    char new_message[1000] = { 0 };

    for (i = 0; i < (int)strlen(LETTERS); i++) {
        key_letters[i] = LETTERS[(i - key + 26) % 26];
    }

    for (i = 0; msg[i]; i++) {
        for (j = 0; j < (int)strlen(LETTERS); j++) {
            if (msg[i] == LETTERS[j]) {
                new_message[i] = key_letters[j];
                break;
            }
            else {
                new_message[i] = msg[i];
            }
        }
    }
    new_message[strlen(msg)] = '\0';

    printf("%s\n", new_message);
}