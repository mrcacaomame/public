#include "aes/aes.h"
#include "base64/base64.h"
#include <random>
#include <iostream>
#include <termios.h>

#define MSG_MAX_SIZE (1024)
#define KEY_SIZE     (32)
#define IV_SIZE      (16)
#define TAG_SIZE     (16)

static void generate_random_bytes (std::vector <uint8_t> &data, size_t size);

int main (int argc, char *argv[]){
    if (argc != 2){
        fprintf(stderr, "\033[1;31mPut the password as the first argument.\033[0m\n");
        return -1;
    }
    if (strlen (argv[1]) >= MSG_MAX_SIZE){
        fprintf (stderr, "\033[1;31mIncorrect password length. The maximum length is %d.\033[0m\n", MSG_MAX_SIZE);
        return -1;
    }
    std::vector<uint8_t> encrypt_data;
    std::vector<uint8_t> decrypt;
    std::vector<uint8_t> message(MSG_MAX_SIZE);
    std::vector<uint8_t> key(KEY_SIZE);
    std::vector<uint8_t> iv(IV_SIZE);
    std::vector<uint8_t> tag(TAG_SIZE);
    int j = 0;
    for (int j = 0; j < strlen (argv[1]); j ++){
        message[j] = static_cast <uint8_t> (argv[1][j]);
    }
    message.resize (strlen (argv[1]));

    // 鍵、初期化ベクトル、タグの生成
    generate_random_bytes(key, KEY_SIZE);
    generate_random_bytes(iv, IV_SIZE);
    // generate_random_bytes(tag, TAG_SIZE);

    // 暗号化
    try {
        common_key::aes_gcm_encrypt(message, key, iv, tag, encrypt_data);
    } catch (common_key_exception& e) {
        fprintf(stderr, "%s\n", e.what());
        return -1;
    }

    // 暗号化データの表示
    std::string encrypt_str, key_str, iv_str, tag_str;
    base64::encode (encrypt_data, encrypt_str);
    base64::encode (key, key_str);
    base64::encode (iv, iv_str);
    base64::encode (tag, tag_str);

    printf ("ENCRYPT_DATA=\"%s\"\n", encrypt_str.c_str ());
    printf ("KEY=\"%s\"\n", key_str.c_str ());
    printf ("IV=\"%s\"\n", iv_str.c_str ());
    printf ("TAG=\"%s\"\n", tag_str.c_str ());

    return 0;
}

void generate_random_bytes(std::vector<uint8_t> &data, size_t size){
    std::random_device rnd;
    data.resize (size);
    for (int j = 0; j < size; j++) {
        uint8_t n = rnd() % 256;
        data[j] = n;
    }
}
