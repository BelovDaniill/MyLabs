#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <iostream>
#include <string.h>
#include <time.h>
#include "vgb.h"


void generate_seed(char* seed_buf, int length) {
    const char charset[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for (int i = 0; i < length; i++) {
        int index = rand() % (sizeof(charset) - 1);
        seed_buf[i] = charset[index];
    }
    seed_buf[length] = '\0';
}

void VGBencrypt(char* message, const char* key, const char* op_type) {
    srand((unsigned int)time(NULL));

    char seed[9];
    generate_seed(seed, 8);

    int seed_len = (int)strlen(seed);
    int key_len = (int)strlen(key);
    char* full_key = (char*)malloc(seed_len + key_len + 1);

    strcpy(full_key, seed);
    strcat(full_key, key);

    int full_key_len = seed_len + key_len;
    int msg_len = (int)strlen(message);
    int j = 0;

    for (int i = 0; i < msg_len; i++) {
        char k_char = full_key[j % full_key_len];

        if (strcmp(op_type, "XOR") == 0) {
            message[i] = message[i] ^ k_char;
        }

        j++;
    }

    printf("Generated Seed: %s\n", seed);
    printf("Encrypted (Hex): ");
    for (int i = 0; i < msg_len; i++) {
        printf("%02X ", (unsigned char)message[i]);
    }
    printf("\n");

    free(full_key);
}

void VGBdecrypt(char* message, const char* key, const char* op_type, const char* seed) {

    int seed_len = (int)strlen(seed);
    int key_len = (int)strlen(key);

    char* full_key = (char*)malloc(seed_len + key_len + 1);
    strcpy(full_key, seed);
    strcat(full_key, key);

    int full_key_len = seed_len + key_len;
    int msg_len = (int)strlen(message);
    int j = 0;

    for (int i = 0; i < msg_len; i++) {
        char k_char = full_key[j % full_key_len];

        if (strcmp(op_type, "XOR") == 0) {
            message[i] = message[i] ^ k_char;
        }

        j++;
    }

    printf("Decrypted (VGB): %s\n", message);

    free(full_key);
}