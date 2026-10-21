#include <iostream>
#include <cstring>
#include "vars.h"
#include "keyReader.h"
using namespace std;

void VGN(char* message) {
    
    char* key = charreadKey();

    int alpha_len = strlen(LETTERS);
    int key_len = strlen(key);
    int msg_len = strlen(message);

    char* encrypt = new char[msg_len + 1];
    int index3 = 0; 

    cout << "enc: ";

    for (int i = 0; i < msg_len; i++) {
        int m1 = -1, m2 = -1;

        for (int j = 0; j < alpha_len; j++) {
            if (message[i] == LETTERS[j]) {
                m1 = j;
                break;
            }
        }

        for (int j = 0; j < alpha_len; j++) {
            if (key[index3] == LETTERS[j]) {
                m2 = j;
                break;
            }
        }

        if (m1 != -1 && m2 != -1) {
            int temp;
            if (m1 + m2 < alpha_len) {
                temp = m1 + m2;
            }
            else {
                temp = m1 + m2 - alpha_len;
            }
            encrypt[i] = LETTERS[temp];
        }
        else {
            encrypt[i] = message[i];
        }

        if (index3 == key_len - 1) {
            index3 = 0;
        }
        else {
            index3++;
        }
    }
    encrypt[msg_len] = '\0';
    cout << encrypt << "\n";
    delete[] encrypt;
}

void VGNDecrypt(char* message) {
    char* key = charreadKey();
    
    int alpha_len = strlen(LETTERS);
    int key_len = strlen(key);
    int msg_len = strlen(message);

    char* decrypt = new char[msg_len + 1];
    int index3 = 0;

    cout << "dec: ";

    for (int i = 0; i < msg_len; i++) {
        int m1 = -1, m2 = -1;

        for (int j = 0; j < alpha_len; j++) {
            if (message[i] == LETTERS[j]) {
                m1 = j;
                break;
            }
        }

        for (int j = 0; j < alpha_len; j++) {
            if (key[index3] == LETTERS[j]) {
                m2 = j;
                break;
            }
        }

        if (m1 != -1 && m2 != -1) {
            int temp;
            if (m1 - m2 >= 0) {
                temp = m1 - m2;
            }
            else {
                temp = m1 - m2 + alpha_len;
            }
            decrypt[i] = LETTERS[temp];
        }
        else {
            decrypt[i] = message[i];
        }

        if (index3 == key_len - 1) {
            index3 = 0;
        }
        else {
            index3++;
        }
    }
    decrypt[msg_len] = '\0';
    cout << decrypt << "\n";
    delete[] decrypt;
}