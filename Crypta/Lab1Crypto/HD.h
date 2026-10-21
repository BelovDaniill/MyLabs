#pragma once
long DH_PublicKey(long g, long x, long p);

long DH_SharedKey(long other_pub, long x, long p);

long generate_prime();

long find_primitive_root(long p);

bool is_prime(long n);

void fulDH();