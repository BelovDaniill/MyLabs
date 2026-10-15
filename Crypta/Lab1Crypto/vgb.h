#pragma once
void VGB_random(char* seed_str, int length);
void VGB_exclud(char* message);
void VGB_encrypt(char* message, const char* key, const char* op_type);
void VGB_decrypt(char* message, const char* key, const char* op_type);