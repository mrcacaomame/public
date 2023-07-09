#include "aes.h"

void common_key::aes_gcm_encrypt(const std::vector<uint8_t>& input, const std::vector<uint8_t>& key, const std::vector<uint8_t>& iv, std::vector<uint8_t>& tag, std::vector<uint8_t>& output) {
    if(key.size() != 32) {
        throw common_key_exception("Key size must be 32 bytes for AES-256-GCM.");
    }
    EVP_CIPHER_CTX* ctx = EVP_CIPHER_CTX_new();
    if(!ctx) {
        throw common_key_exception("Failed to create new EVP_CIPHER_CTX.");
    }
    if(!EVP_EncryptInit_ex(ctx, EVP_aes_256_gcm(), nullptr, nullptr, nullptr)) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to initialize encryption context.");
    }
    if(!EVP_CIPHER_CTX_ctrl(ctx, EVP_CTRL_GCM_SET_IVLEN, iv.size(), nullptr)) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to set IV length.");
    }
    if(!EVP_EncryptInit_ex(ctx, nullptr, nullptr, key.data(), iv.data())) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to initialize key and IV.");
    }
    output.resize(input.size());
    int len1 = 0;
    if(!EVP_EncryptUpdate(ctx, output.data(), &len1, input.data(), input.size())) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to encrypt data.");
    }
    int len2 = 0;
    if(!EVP_EncryptFinal_ex(ctx, output.data() + len1, &len2)) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to finalize encryption.");
    }
    if(!EVP_CIPHER_CTX_ctrl(ctx, EVP_CTRL_GCM_GET_TAG, 16, tag.data())) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to get authentication tag.");
    }
    EVP_CIPHER_CTX_free(ctx);
}

void common_key::aes_gcm_encrypt(const std::vector<uint8_t>& input, const std::vector<uint8_t>& key, const std::vector<uint8_t>& iv, std::vector<uint8_t>& output) {
    std::vector<uint8_t> tag(16); // GCM authentication tag
    common_key::aes_gcm_encrypt(input, key, iv, tag, output);
}

void common_key::aes_gcm_decrypt(const std::vector<uint8_t>& input, const std::vector<uint8_t>& key, const std::vector<uint8_t>& iv, const std::vector<uint8_t>& tag, std::vector<uint8_t>& output) {
    if(key.size() != 32) {
        throw common_key_exception("Key size must be 32 bytes for AES-256-GCM.");
    }
    EVP_CIPHER_CTX* ctx = EVP_CIPHER_CTX_new();
    if(!ctx) {
        throw common_key_exception("Failed to create new EVP_CIPHER_CTX.");
    }
    if(!EVP_DecryptInit_ex(ctx, EVP_aes_256_gcm(), nullptr, nullptr, nullptr)) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to initialize decryption context.");
    }
    if(!EVP_CIPHER_CTX_ctrl(ctx, EVP_CTRL_GCM_SET_IVLEN, iv.size(), nullptr)) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to set IV length.");
    }
    if(!EVP_DecryptInit_ex(ctx, nullptr, nullptr, key.data(), iv.data())) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to initialize key and IV.");
    }
    output.resize(input.size());
    int len1 = 0;
    if(!EVP_DecryptUpdate(ctx, output.data(), &len1, input.data(), input.size())) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to decrypt data.");
    }
    if(!EVP_CIPHER_CTX_ctrl(ctx, EVP_CTRL_GCM_SET_TAG, tag.size(), const_cast<uint8_t*>(tag.data()))) {
        EVP_CIPHER_CTX_free(ctx);
        throw common_key_exception("Failed to set authentication tag.");
    }
    int len2 = 0;
    int ret = EVP_DecryptFinal_ex(ctx, output.data() + len1, &len2);
    EVP_CIPHER_CTX_free(ctx);
    if(ret <= 0) {
        throw common_key_exception("Failed to finalize decryption. Authentication tag mismatch.");
    }
}
