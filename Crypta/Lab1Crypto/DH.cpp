#include <iostream>
#include "invmod.h"
using namespace std;


long DH_PublicKey(long g, long x, long p) {
    long pub_key = pow_mod(g, x, p);
    cout << "DH Public Key: " << pub_key << "\n";
    return pub_key;
}

long DH_SharedKey(long other_pub, long x, long p) {
    long shared_key = pow_mod(other_pub, x, p);
    cout << "DH Shared Secret: " << shared_key << "\n";
    return shared_key;
}

bool is_prime(long n) {
    if (n <= 1) return false;
    for (long i = 2; i * i <= n; i++) {
        if (n % i == 0) return false;
    }
    return true;
}

long generate_prime() {
    long p = 10;
    while (true) {
        if (is_prime(p)) {
            return p;
        }
        p++;
    }
}

long find_primitive_root(long p) {
    for (long candidate = 2; candidate < p; candidate++) {
        long val = pow_mod(candidate, (p - 1) / 2, p);
        if (val != 1 && val != p - 1) {
            return candidate;
        }
    }
    return 2;
}


void fulDH() {
    long p = 0, g = 0;

    p = generate_prime();
    g = find_primitive_root(p);


    cout << "=== DH Parameters" << endl;
    cout << "Prime (p): " << p << endl;
    cout << "Generator (g): " << g << endl << endl;

    long alice_private = 6;
    long bob_private = 15;

    long alice_public = DH_PublicKey(g, alice_private, p);
    long bob_public = DH_PublicKey(g, bob_private, p);

    cout << "=== Public Keys" << endl;
    cout << "Alice Public Key: " << alice_public << endl;
    cout << "Bob Public Key:   " << bob_public << endl << endl;

    long alice_shared = DH_SharedKey(bob_public, alice_private, p);
    long bob_shared = DH_SharedKey(alice_public, bob_private, p);

    cout << "=== Shared Secrets" << endl;
    cout << "Alice's calculated shared secret: " << alice_shared << endl;
    cout << "Bob's calculated shared secret:   " << bob_shared << endl;
}