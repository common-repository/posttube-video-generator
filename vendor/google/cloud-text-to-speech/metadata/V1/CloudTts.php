<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/texttospeech/v1/cloud_tts.proto

namespace GPBMetadata\Google\Cloud\Texttospeech\V1;

class CloudTts
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Annotations::initOnce();
        \GPBMetadata\Google\Api\Client::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0aca0f0a2c676f6f676c652f636c6f75642f74657874746f7370656563682f76312f636c6f75645f7474732e70726f746f121c676f6f676c652e636c6f75642e74657874746f7370656563682e76311a17676f6f676c652f6170692f636c69656e742e70726f746f1a1f676f6f676c652f6170692f6669656c645f6265686176696f722e70726f746f222f0a114c697374566f6963657352657175657374121a0a0d6c616e67756167655f636f64651801200128094203e0410122490a124c697374566f69636573526573706f6e736512330a06766f6963657318012003280b32232e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e566f6963652294010a05566f69636512160a0e6c616e67756167655f636f646573180120032809120c0a046e616d6518022001280912420a0b73736d6c5f67656e64657218032001280e322d2e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e53736d6c566f69636547656e64657212210a196e61747572616c5f73616d706c655f726174655f686572747a18042001280522e9010a1753796e74686573697a655370656563685265717565737412400a05696e70757418012001280b322c2e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e53796e746865736973496e7075744203e0410212460a05766f69636518022001280b32322e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e566f69636553656c656374696f6e506172616d734203e0410212440a0c617564696f5f636f6e66696718032001280b32292e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e417564696f436f6e6669674203e0410222400a0e53796e746865736973496e707574120e0a04746578741801200128094800120e0a0473736d6c1802200128094800420e0a0c696e7075745f736f757263652284010a14566f69636553656c656374696f6e506172616d73121a0a0d6c616e67756167655f636f64651801200128094203e04102120c0a046e616d6518022001280912420a0b73736d6c5f67656e64657218032001280e322d2e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e53736d6c566f69636547656e64657222f1010a0b417564696f436f6e66696712480a0e617564696f5f656e636f64696e6718012001280e322b2e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e417564696f456e636f64696e674203e04102121d0a0d737065616b696e675f726174651802200128014206e04104e0410112150a0570697463681803200128014206e04104e04101121e0a0e766f6c756d655f6761696e5f64621804200128014206e04104e04101121e0a1173616d706c655f726174655f686572747a1805200128054203e0410112220a12656666656374735f70726f66696c655f69641806200328094206e04104e0410122310a1853796e74686573697a65537065656368526573706f6e736512150a0d617564696f5f636f6e74656e7418012001280c2a570a0f53736d6c566f69636547656e64657212210a1d53534d4c5f564f4943455f47454e4445525f554e535045434946494544100012080a044d414c451001120a0a0646454d414c451002120b0a074e45555452414c10032a540a0d417564696f456e636f64696e67121e0a1a415544494f5f454e434f44494e475f554e5350454349464945441000120c0a084c494e4541523136100112070a034d50331002120c0a084f47475f4f505553100332b4030a0c54657874546f5370656563681293010a0a4c697374566f69636573122f2e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e4c697374566f69636573526571756573741a302e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e4c697374566f69636573526573706f6e7365222282d3e493020c120a2f76312f766f69636573da410d6c616e67756167655f636f646512bc010a1053796e74686573697a6553706565636812352e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e53796e74686573697a65537065656368526571756573741a362e676f6f676c652e636c6f75642e74657874746f7370656563682e76312e53796e74686573697a65537065656368526573706f6e7365223982d3e493021822132f76312f746578743a73796e74686573697a653a012ada4118696e7075742c766f6963652c617564696f5f636f6e6669671a4fca411b74657874746f7370656563682e676f6f676c65617069732e636f6dd2412e68747470733a2f2f7777772e676f6f676c65617069732e636f6d2f617574682f636c6f75642d706c6174666f726d42e4010a20636f6d2e676f6f676c652e636c6f75642e74657874746f7370656563682e7631421154657874546f53706565636850726f746f50015a48676f6f676c652e676f6c616e672e6f72672f67656e70726f746f2f676f6f676c65617069732f636c6f75642f74657874746f7370656563682f76313b74657874746f737065656368f80101aa021c476f6f676c652e436c6f75642e54657874546f5370656563682e5631ca021c476f6f676c655c436c6f75645c54657874546f5370656563685c5631ea021f476f6f676c653a3a436c6f75643a3a54657874546f5370656563683a3a5631620670726f746f33"
        ), true);

        static::$is_initialized = true;
    }
}

