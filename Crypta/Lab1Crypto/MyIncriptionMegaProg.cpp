#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <iostream>
#include "SimpleCesar.h"
#include "WordCesar.h"
#include "VGN.h"
#include "vgb.h"
#include "invmod.h"
#include "HD.h"

//"D:\studies\laboratories\Crypta\Lab1Crypto\x64\Debug\Lab1Crypto.exe" - путь

using namespace std;

int main(int argc, char* argv[]) {
    if (argc == 1) {
        cerr << "crypto: no option and message\n";
        return 3;
    }

    if (argc == 2) {
        if (argv[1][0] == '-') {
            cerr << "crypto: no message/parameters\n";
        }
        else {
            cerr << "crypto: no option\n";
        }
        return 2;
    }

    if (argc == 3) {
        char* opt = argv[1];
        char* msg = argv[2];
        if (strcmp(opt, "-Cesar") == 0) {
            Cesar(msg);
        }
        else if(strcmp(opt, "-CesarDecrypt") == 0) {
            CesarDecrypt(msg);
        }
        else if (strcmp(opt, "-WordCesar") == 0) {
            WordCesar(msg);
                }
        else if (strcmp(opt, "-WordCesarDecrypt") == 0) {
            WordCesarDecrypt(msg);
        }
        else if (strcmp(opt, "-VGN") == 0) {
            VGN(msg);
        }
        else if (strcmp(opt, "-VGNDecrypt") == 0) {
            VGNDecrypt(msg);
        }
        else if (strcmp(opt, "-DH") == 0) {
            fulDH();
        }
        else if (strcmp(opt, "-VGB") == 0) {
            VGBencrypt(msg, "TFT", "XOR");
        }
        else if (strcmp(opt, "-VGBDecrypt") == 0) {
            VGBdecrypt(msg, "TFT", "XOR", "YOWWSFGF");
        }
        else {
            cerr << "unknown option\n";
            exit(3);
            }
        }

    if (argc == 4) {
        char* opt = argv[1];
        long long b = atol(argv[2]);
        long long n = atol(argv[3]);
        long long result = -1;

        if (strcmp(opt, "-MI-eea") == 0) {
            result = modEEA(b, n);
        }
        else if (strcmp(opt, "-MI-ferma") == 0) {
            result = modFERMA(b, n);
        }
        else if (strcmp(opt, "-MI-gcd") == 0) {
            result = modEEAbi(b, n);
        }
        else {
            cerr << "unknown option\n";
            exit(3);
        }

        if (result == -1) {
            cout << "The modular inverse does not exist.\n";
        }
        else {
            cout << "Result inverse: " << result << "\n";
        }
        return 0;
    }

    if (argc > 4) {
        cerr << "crypto: too many strings\n";
        return 4;
    }

    return 0;
}