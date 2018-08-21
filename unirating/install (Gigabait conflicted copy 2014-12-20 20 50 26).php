<?xml version="1.0" encoding="UTF-8"?>
<assembly xmlns="urn:schemas-microsoft-com:asm.v3" manifestVersion="1.0" copyright="Copyright (c) Microsoft Corporation. All Rights Reserved.">
  <assemblyIdentity name="Microsoft-Windows-DeviceMetadataParsers" version="6.1.7600.16385" processorArchitecture="amd64" language="neutral" buildType="release" publicKeyToken="31bf3856ad364e35" versionScope="nonSxS" />
  <file name="DeviceMetadataParsers.dll" destinationPath="$(runtime.system32)\" sourceName="DeviceMetadataParsers.dll" sourcePath=".\" importPath="$(build.nttree)\">
    <securityDescriptor name="WRP_FILE_DEFAULT_SDDL" />
    <asmv2:hash xmlns:asmv2="urn:schemas-microsoft-com:asm.v2">
      <dsig:Transforms xmlns:dsig="http://www.w3.org/2000/09/xmldsig#">
        <dsig:Transform Algorithm="urn:schemas-microsoft-com:HashTransforms.Identity" />
      </dsig:Transforms>
      <dsig:DigestMethod xmlns:dsig="http://www.w3.org/2000/09/xmldsig#" Algorithm="http://www.w3.org/2000/09/xmldsig#sha256" />
      <dsig:DigestValue xmlns:dsig="http://www.w3.org/2000/09/xmldsig#">UVg7rVg6wAUfWdQN1T5GJ7OELsQKUE10enz81J3zfaQ=</dsig:DigestValue>
    </asmv2:hash>
  </file>
  <registryKeys>
    <registryKey keyName="HKEY_CLASSES_ROOT\CLSID\{7754801c-b01d-4d74-84cb-dfd70669ffbe}\" owner="false">
      <registryValue name="" valueType="REG_SZ" value="DeviceInfo.xml parser" operationHint="replace" owner="true" />
      <securityDescriptor name="WRP_REGKEY_DEFAULT_SDDL" />
    </registryKey>
    <registryKey keyName="HKEY_CLASSES_ROOT\CLSID\{7754801c-b01d-4d74-84cb-dfd70669ffbe}\InprocServer32\" owner="false">
      <registryValue name="" valueType="REG_SZ" value="$(runtime.system32)\DeviceMetadataParsers.dll" operationHint="replace" owner="true" />
      <registryValue name="ThreadingModel" valueType="REG_SZ" value="Free" ope