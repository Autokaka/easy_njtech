# Easy Njtech

南京工业大学教务系统定制化 API, 在正方返回数据的基础上加入了更多的学校定制化信息, 可用于更加精准的信息检索

## 写在一切的开头

- 本 API 虽然十分简单, 但**并不开放给 PHP 入门选手**.

- 本 API 的拓展解决方案(彩蛋, 白送的, 自行察觉)需要您掌握 Aliyun OSS 的基础知识.

- 学校很多快速的接口变动并不能保证及时在本仓库更新, 但一定保证在本人**在校生涯**能察觉到的最快时间内更新.

- 出于校方利益和校园安全考虑, 本人无法开放 API 的完整版本, 仅留下合理范围内的接口实现.

- 本人维护的 API 向来优先自己的服务器, 在其基础上做出**合理精简和最大化通用性适配**后提交到本仓库. 本 API 现有功能及其每一次迭代都处于精心考虑, 经测试**完全够用**, 如果您需要定制化内容, 请在 Issue 或直接联系本人寻求**更多解决方案**.

- 本人最近在逐渐开源本人学术生涯初期的部分较为完善, 且不影响多方利益的源码, 如果您感兴趣, 不妨给个 Star. 如果有任何问题, 请务必发起 Issue 或者 PR, 秋梨膏.

## 这 API 怎么用?

1. 您必须得有 PHP7 以上的运行环境
   本 API 基于 Symfony Crawler 和 Aliyun OSS, 综合两者的最佳考虑, 需要 PHP7 及以上的运行环境.

2. 开放 PHP 的部分限制方法
   部分 PHP 方法受到较高的系统安全管控, 默认情况下是关闭的, 您必须全部开启他们, 具体的开启清单, 您可以在运行本 API 一次以后, 在返回数据的报错提示内开启. 全部开启完毕后, API 即可正常使用.

3. 访问本 API
   将 API 部署到您的网站目录, 直接请求接口文件, 通过 Postman 等 API 测试工具, 访问您部署路径上的 API, 如: https://api.dogshitpiestudio.cn/easy-njtech/api-dev/app/check_update.php

## 目前可以正常工作的部分

- 学生类

  - 账号登录, 获取个人完整信息.

  - 学生课表查询, **包括开学第一周信息, 当前所处周信息自动监测功能**.

  - 学生成绩查询, 查询学生在校全部学生信息.

- APP 类

  - 支持任何平台 APP 版本更新检测, 只要您的版本号满足 "x.x.x+y" 的形式 (尾号的 +y 可以不做附加, 但出于版本号命名规范, 建议加上).

  - 平台错误报告检测简易上报接口, 方便使用本 API 的开发者直接获取平台 APP 运行错误信息.

## 额外声明

- 本 API 仅供学术交流, 请勿用于任何商业用途, 基于本 API 的二次开发均与本人**毫无关联**.

- crypto.php 内为加密解密方法, 仅使用 base64 作为**形式化案例**. 为了数据安全考虑, 使用本 API 时, 在能力范围内, 请实现您自己的加密和解密方法.