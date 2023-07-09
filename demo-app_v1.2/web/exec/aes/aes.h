#ifndef __MODULES_COMMON_KEY_AES_H__
#define __MODULES_COMMON_KEY_AES_H__
#include <unistd.h>
#include <stdint.h>
#include <memory.h>
#include <vector>
#include <openssl/evp.h>

#include "aes_exception.h"
#define ENCRYPT_KEY_BITS (256)
namespace common_key{
    void aes_gcm_encrypt (const std::vector<uint8_t> &input, const std::vector<uint8_t> &key, const std::vector<uint8_t> &iv, std::vector<uint8_t> &tag, std::vector<uint8_t> &output);
    void aes_gcm_encrypt (const std::vector<uint8_t> &input, const std::vector<uint8_t> &key, const std::vector<uint8_t> &iv, std::vector<uint8_t> &output);
    
    void aes_gcm_decrypt(const std::vector<uint8_t> &input, const std::vector<uint8_t> &key, const std::vector<uint8_t> &iv, const std::vector<uint8_t> &tag, std::vector<uint8_t> &output);
}

#endif