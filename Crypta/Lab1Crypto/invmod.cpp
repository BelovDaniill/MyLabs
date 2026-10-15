#include <stdio.h>
#include <stdlib.h>
#include "invmod.h"

// Вспомогательная функция модульного возведения в степень (нужна для Ферма)
static long long pow_mod(long long a, long long k, long long n) {
    long long b_val = 1;
    a = a % n;
    while (k > 0) {
        if (k % 2 == 1) {
            b_val = (b_val * a) % n;
        }
        k /= 2;
        a = (a * a) % n;
    }
    return b_val;
}

// 3.3.1 Расширенный алгоритм Евклида (EEA)
long long mod_inverse_eea(long long b, long long n) {
    long long n0 = n, b0 = b;
    long long t0 = 0, t = 1;
    long long q = n0 / b0;
    long long r = n0 - q * b0;
    long long temp = 0;

    if (b <= 1 || n <= 0) return -1;

    while (r > 0) {
        temp = t0 - q * t;
        if (temp >= 0) {
            temp = temp % n;
        }
        else {
            temp = n - ((-temp) % n);
        }
        n0 = b0;
        b0 = r;
        t0 = t;
        t = temp;
        q = n0 / b0;
        r = n0 - q * b0;
    }

    if (b0 != 1) {
        return -1; // Обратный элемент не существует
    }
    return t;
}

// 3.3.2 Метод Ферма (через модулярное возведение в степень, только для простых n)
long long mod_inverse_fermat(long long b, long long p) {
    // По Малой теореме Ферма: b^(-1) === b^(p-2) (mod p)
    return pow_mod(b, p - 2, p);
}

// 3.3.3 Двоичный алгоритм Евклида (Binary GCD)
long long mod_inverse_gcd(long long b, long long n) {
    // Упрощенная реализация бинарного алгоритма для нахождения обратного элемента
    long long u = b, v = n;
    long long x1 = 1, x2 = 0;

    while (u != 1 && v != 1) {
        while (u % 2 == 0) {
            u /= 2;
            if (x1 % 2 == 0) x1 /= 2;
            else x1 = (x1 + n) / 2;
        }
        while (v % 2 == 0) {
            v /= 2;
            if (x2 % 2 == 0) x2 /= 2;
            else x2 = (x2 + n) / 2;
        }
        if (u >= v) {
            u -= v;
            x1 = (x1 - x2 >= 0) ? (x1 - x2) % n : (x1 - x2 + n) % n;
        }
        else {
            v -= u;
            x2 = (x2 - x1 >= 0) ? (x2 - x1) % n : (x2 - x1 + n) % n;
        }
    }
    if (u == 1) return (x1 % n + n) % n;
    return (x2 % n + n) % n;
}