#pragma once


void generate_seed(char* seed_buf, int length);
void VGBencrypt(char* message, const char* key, const char* op_type);
void VGBdecrypt(char* message, const char* key, const char* op_type, const char* seed);
