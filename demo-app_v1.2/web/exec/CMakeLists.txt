cmake_minimum_required (VERSION 3.10)

find_package(OpenSSL REQUIRED)

file (GLOB_RECURSE MODULES_SRC
    "${CMAKE_CURRENT_SOURCE_DIR}/aes/*.cc"
    "${CMAKE_CURRENT_SOURCE_DIR}/base64/*.cc"
)

add_executable (aes256_gcm_decrypt 
    ${CMAKE_SOURCE_DIR}/aes256_gcm_decrypt
    ${MODULES_SRC}
)
target_link_libraries (aes256_gcm_decrypt OpenSSL::Crypto)

add_executable (aes256_gcm_encrypt
    ${CMAKE_SOURCE_DIR}/aes256_gcm_encrypt
    ${MODULES_SRC}
)
target_link_libraries (aes256_gcm_encrypt OpenSSL::Crypto)

install(TARGETS aes256_gcm_decrypt aes256_gcm_encrypt
        RUNTIME DESTINATION /usr/local/bin)
