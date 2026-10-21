#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "WordCesar.h"
#include "vars.h"
#include "keyReader.h"

void WordCesar(char* msg) {
    int i, j;

    char* key = charreadKey();
    if (!key) {
        printf("Error reading key\n");
        return;
    }

    char key_letters[200] = { 0 };
    char new_message[1000] = { 0 };

    int key_len = (int)strlen(key);
    int letters_len = (int)strlen(LETTERS);

    for (i = 0; i < key_len; i++) {
        key_letters[i] = key[i];
    }

    for (i = 0; i < letters_len; i++) {
        key_letters[i + key_len] = LETTERS[i];
    }

    // удаление повторов
    int seen[256] = { 0 };
    int write_index = 0;
    int total_len = key_len + letters_len;

    for (i = 0; i < total_len; i++) {
        unsigned char c = (unsigned char)key_letters[i];
        if (c == '\0') break;

        if (!seen[c]) {
            seen[c] = 1;
            key_letters[write_index++] = key_letters[i];
        }
    }
    key_letters[write_index] = '\0';

    for (i = 0; msg[i] != '\0'; i++) {
        char c = msg[i];
        int found = 0;

        for (j = 0; j < 26; j++) {
            if (c == LETTERS[j]) {
                new_message[i] = key_letters[j];
                found = 1;
                break;
            }
        }
        if (!found) {
            new_message[i] = c;
        }
    }
    new_message[strlen(msg)] = '\0';

    printf("Encrypted message: %s\n", new_message);
}

void WordCesarDecrypt(char* msg) {
    int i, j;

    char* key = charreadKey();
    if (!key) {
        printf("Error reading key\n");
        return;
    }

    char key_letters[256] = { 0 };
    char new_message[1000] = { 0 };

    int key_len = (int)strlen(key);
    int letters_len = (int)strlen(LETTERS);

    for (i = 0; key_len > i; i++) {
        key_letters[i] = key[i];
    }

    for (i = 0; i < letters_len; i++) {
        key_letters[i + key_len] = LETTERS[i];
    }

    int seen[256] = { 0 };
    int write_index = 0;
    int total_len = key_len + letters_len;

    for (i = 0; i < total_len; i++) {
        unsigned char c = (unsigned char)key_letters[i];
        if (c == '\0') break;

        if (!seen[c]) {
            seen[c] = 1;
            key_letters[write_index++] = key_letters[i];
        }
    }
    key_letters[write_index] = '\0';

    for (i = 0; msg[i] != '\0'; i++) {
        char c = msg[i];
        int found = 0;

        for (j = 0; j < 26; j++) {
            if (c == key_letters[j]) {
                new_message[i] = LETTERS[j];
                found = 1;
                break;
            }
        }
        if (!found) {
            new_message[i] = c;
        }
    }
    new_message[strlen(msg)] = '\0';

    printf("Decrypted message: %s\n", new_message);
}