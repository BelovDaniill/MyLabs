#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <iostream>
#include "SimpleCesar.h"
#include "WordCesar.h"
#include "VGN.h"
#include "invmod.h" // Подключаем наш новый модуль

using namespace std;

int main(int argc, char* argv[]) {
    if (argc == 1) {
        std::cerr << "crypto: no option and message\n";
        return 3;
    }

    if (argc == 2) {
        if (argv[1][0] == '-') {
            std::cerr << "crypto: no message/parameters\n";
        }
        else {
            std::cerr << "crypto: no option\n";
        }
        return 2;
    }

    // Пример вызова для модульной обратной величины (ожидаем b и n, передаваемых через аргументы)
    if (argc == 4) {
        char* opt = argv[1];
        long long b = atoll(argv[2]);
        long long n = atoll(argv[3]);
        long long result = -1;

        if (strcmp(opt, "-MI-eea") == 0) {
            result = mod_inverse_eea(b, n);
        }
        else if (strcmp(opt, "-MI-ferma") == 0) {
            result = mod_inverse_fermat(b, n);
        }
        else if (strcmp(opt, "-MI-gcd") == 0) {
            result = mod_inverse_gcd(b, n);
        }
        else {
            std::cerr << "unknown option\n";
            exit(3);
        }

        if (result == -1) {
            std::cout << "The modular inverse does not exist.\n";
        }
        else {
            std::cout << "Result inverse: " << result << "\n";
        }
        return 0;
    }

    // Существующая логика для шифров (argc == 3)
    if (argc == 3) {
        char* opt = argv[1];
        char* msg = argv[2];
        if (strcmp(opt, "-Cesar") == 0) {
            Cesar(msg);
        }
        // ... остальные ветки шифров ...
        else {
            cerr << "unknown option\n";
            exit(3);
        }
    }

    if (argc > 4) {
        std::cerr << "crypto: too many strings\n";
        return 4;
    }

    return 0;
}