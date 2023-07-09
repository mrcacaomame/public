#include "base64.h"

static std::string base64_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

void base64::encode (const std::vector <uint8_t> &input, std::string &output) noexcept{
    size_t input_len = input.size ();
    uint8_t input_bytes[3];
    uint8_t encode_bytes[4];
    size_t num_bytes;
    for (size_t j = 0; j < input_len; j += 3){
        num_bytes = 0;
        for (size_t k = 0; k < 3; k ++){
            if (j + k < input_len){
                input_bytes[k] = input[j + k];
                num_bytes ++;
            }
        }
        encode_bytes[0] = base64_chars[(input_bytes[0] & 0xFC) >> 2];
        encode_bytes[1] = base64_chars[((input_bytes[0] & 0x03) << 4) | ((input_bytes[1] & 0xF0) >> 4)];
        encode_bytes[2] = (num_bytes > 1) ? base64_chars[((input_bytes[1] & 0x0F) << 2) | ((input_bytes[2] & 0xC0) >> 6)] : '=';
        encode_bytes[3] = (num_bytes > 2) ? base64_chars[input_bytes[2] & 0x3F] : '=';
        output.append (reinterpret_cast <char*> (encode_bytes), 4);
    }
}


void base64::decode(const std::string& input, std::vector<uint8_t>& output) noexcept{
    size_t input_len = input.size();
    uint8_t decode_bytes[4];
    size_t num_bytes = 0;
    for (size_t i = 0; i < input_len; i += 4) {
        for (size_t j = 0; j < 4; j++) {
            if (i + j < input_len) {
                size_t index = base64_chars.find(input[i + j]);
                if (index != std::string::npos) {
                    decode_bytes[j] = static_cast<uint8_t>(index);
                    if (input[i + j] != '=')
                    num_bytes++;
                }
            }
        }
        if (num_bytes >= 1)
            output.push_back((decode_bytes[0] << 2) | (decode_bytes[1] >> 4));
        if (num_bytes >= 2)
            output.push_back((decode_bytes[1] << 4) | (decode_bytes[2] >> 2));
        if (num_bytes >= 3)
            output.push_back((decode_bytes[2] << 6) | decode_bytes[3]);
    }
    if (!output.empty()) {
        size_t padding_count = 0;
        if (input_len >= 2 && input[input_len - 1] == '=' && input[input_len - 2] == '=')
            padding_count = 2;
        else if (input_len >= 1 && input[input_len - 1] == '=')
            padding_count = 1;
        output.resize(output.size() - padding_count);
    }
}
    