#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "vgb.h"

// Функция для генерации/распределения затравочного значения (seed) по длине сообщения
void VGB_random(char* seed_str, int length) {
    // Псевдослучайное заполнение или распределение hash/seed
    srand((unsigned int)time(NULL));
    for (int i = 0; i < length - 1; i++) {
        // Генерируем печатные символы для создания динамического «солящего» потока
        seed_str[i] = (char)('A' + (rand() % 26));
    }
    seed_str[length - 1] = '\0';
}

// Функция для замены/исключения специальных символов после двоичного обхода
void VGB_exclud(char* message) {
    for (int i = 0; message[i] != '\0'; i++) {
        // Если символ вышел за рамки печатных/алфавитных, приводим его к безопасному диапазону
        if (message[i] < 32 || message[i] > 126) {
            message[i] = '?';
        }
    }
}

// Функция шифрования с использованием битовых операций (XOR, AND, OR) и seed
void VGB_encrypt(char* message, const char* key, const char* op_type) {
    int msg_len = (int)strlen(message);
    int key_len = (int)strlen(key);
    int j = 0;

    for (int i = 0; i < msg_len; i++) {
        char k_char = key[j % key_len];

        // Применение выбранной битовой операции
        if (strcmp(op_type, "XOR") == 0) {
            message[i] = message[i] ^ k_char;
        }
        else if (strcmp(op_type, "AND") == 0) {
            message[i] = message[i] & k_char;
        }
        else if (strcmp(op_type, "OR") == 0) {
            message[i] = message[i] | k_char;
        }

        j++;
    }

    // Обработка специальных символов
    VGB_exclud(message);
    printf("Encrypted (VGB): %s\n", message);
}

// Функция дешифрования с использованием битовых операций
void VGB_decrypt(char* message, const char* key, const char* op_type) {
    int msg_len = (int)strlen(message);
    int key_len = (int)strlen(key);
    int j = 0;

    // Для XOR обратная операция идентична прямой
    for (int i = 0; i < msg_len; i++) {
        char k_char = key[j % key_len];

        if (strcmp(op_type, "XOR") == 0) {
            message[i] = message[i] ^ k_char;
        }
        // Для AND/OR в общем случае требуется сохранение исходных битовых масок, 
        // но базовая логика симметричных обратных преобразований реализуется здесь:
        else if (strcmp(op_type, "AND") == 0) {
            message[i] = message[i] | k_char; // условная симметрия для демонстрации
        }
        else if (strcmp(op_type, "OR") == 0) {
            message[i] = message[i] & k_char;
        }

        j++;
    }

    printf("Decrypted (VGB): %s\n", message);
}