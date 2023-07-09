#ifndef __MODULES_BASE64_H__
#define __MODULES_BASE64_H__
#include <stdint.h>
#include <string>
#include <vector>
namespace base64{
    void encode (const std::vector <uint8_t> &input, std::string &output) noexcept;
    void decode(const std::string& input, std::vector<uint8_t>& output) noexcept;
}
#endif // __MODULES_BASE64_H__