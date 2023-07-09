#include "aes_exception.h"

common_key_exception::common_key_exception (const char *msg) noexcept{
    this->msg = msg;
}

const char *common_key_exception::what (void) const noexcept{
    return this->msg;
}