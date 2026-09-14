#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// путь до exe: "D:\studies\laboratories\Crypta\Crypta_1st_prog\x64\Debug\Crypta_1st_prog.exe"

char LETTERS[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

void snth(char* key) {
    int i, j, k;
    strcat(key, LETTERS);

    for (i = 0; i < strlen(key); i++) {
        for (j = i + 1; j < strlen(key); j++) {
            if (key[i] == key[j]) {
                for (k = j; k < strlen(key); k++) {
                    key[k] = key[k + 1];
                }
                j--;
            }
        }
    }
}

void CSR_ENCRYPT(char* msg) {
    int i, j, k, key_shift;
    char keyword[100];

    printf("Enter keyword: ");
    scanf("%s", keyword);

    printf("Enter keyshift: ");
    scanf("%i", &key_shift);

    snth(keyword);

    char enc[100];
    int len = strlen(msg);

    for (i = 0; i < len; i++) {
        for (j = 0; j < 26; j++) {
            if (msg[i] == LETTERS[j]) {
                int new_pos = (j + key_shift) % 26;
                if (new_pos < 0) new_pos += 26;

                enc[i] = keyword[new_pos];
                break;
            }
            else {
                enc[i] = msg[i];
            }
        }
    }
    enc[len] = '\0';

    printf("Result: %s\n", enc);
}

void CSR_DECRYPT(char* msg) {
    int i, j, k, key_shift;
    char keyword[100];

    printf("Enter keyword: ");
    scanf("%s", keyword);

    printf("Enter keyshift: ");
    scanf("%i", &key_shift);

    snth(keyword);

    char dec[100];
    int len = strlen(msg);

    for (i = 0; i < len; i++) {
        for (j = 0; j < 26; j++) {
            if (msg[i] == keyword[j]) {
                int orig_pos = (j - key_shift) % 26;
                if (orig_pos < 0) orig_pos += 26;

                dec[i] = LETTERS[orig_pos];
                break;
            }
            else {
                dec[i] = msg[i];
            }
        }
    }
    dec[len] = '\0';

    printf("Result: %s\n", dec);
}

int main(int argc, char* argv[]) {
    char* prg = argv[0];
    char* opt = argv[1];
    char* msg = argv[2];

    if (argc == 1) {
        fprintf(stderr, "%s: no option and message\n", prg);
        exit(1);
    }
    if (argc == 2) {
        if (opt[0] == '-') {
            fprintf(stderr, "%s: no message\n", prg);
            exit(2);
        }
        else {
            fprintf(stderr, "%s: no option\n", prg);
            exit(2);
        }
    }

    if (argc == 3) {
        if (strcmp(opt, "-Ecsr") == 0) {
            CSR_ENCRYPT(msg);
        }
        else if (strcmp(opt, "-Dcsr") == 0) {
            CSR_DECRYPT(msg);
        }
        else {
            fprintf(stderr, "%s: unknown option '%s'\n", prg, opt);
            exit(3);
        }
    }

    return 0;
}